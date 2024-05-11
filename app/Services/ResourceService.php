<?php

namespace App\Services;

use App\Repositories\Contracts\RoomRepositoryContract;
use App\Services\Contracts\RabbitMQServiceContract;
use Exception;
use PDOException;
use App\Helpers\GFArray;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\BusinessClasses\FileUploadHandler;
use App\Exceptions\ImportDataFailedException;
use App\Exceptions\DatabaseConflictException;
use App\Jobs\CacheModuleClassesWithFewStudents;
use App\Services\Contracts\ExcelServiceContract;
use App\Repositories\Contracts\ClassRepositoryContract;
use App\Repositories\Contracts\ModuleRepositoryContract;
use App\Repositories\Contracts\StudentRepositoryContract;
use App\Repositories\Contracts\ScheduleRepositoryContract;
use App\Repositories\Contracts\CurriculumRepositoryContract;
use App\Repositories\Contracts\ModuleClassRepositoryContract;
use Illuminate\Contracts\Container\BindingResolutionException;
use App\Repositories\Contracts\ExamScheduleRepositoryContract;
use App\Repositories\Contracts\StudySessionRepositoryContract;
use App\Repositories\Contracts\DataVersionStudentRepositoryContract;
use PhpAmqpLib\Message\AMQPMessage;

class ResourceService implements Contracts\ResourceServiceContract
{
    private ModuleClassRepositoryContract $moduleClassRepository;
    private StudentRepositoryContract $studentRepository;
    private ModuleRepositoryContract $moduleRepository;
    private ClassRepositoryContract $classRepository;
    private ScheduleRepositoryContract $scheduleRepository;
    private RoomRepositoryContract $roomRepository;
    private ExamScheduleRepositoryContract $examScheduleRepository;
    private CurriculumRepositoryContract $curriculumRepository;
    private StudySessionRepositoryContract $studySessionRepository;
    private DataVersionStudentRepositoryContract $dataVersionStudentRepository;
    private FileUploadHandler $fileUploadHandler;
    private ExcelServiceContract $excelService;
    private RabbitMQServiceContract $rabbitMQService;

    private const MINIMUM_NUMBER_OF_STUDENTS = 40;

    /**
     * @param ModuleClassRepositoryContract        $moduleClassRepository
     * @param StudentRepositoryContract            $studentRepository
     * @param ModuleRepositoryContract             $moduleRepository
     * @param ClassRepositoryContract              $classRepository
     * @param ScheduleRepositoryContract           $scheduleRepository
     * @param ExamScheduleRepositoryContract       $examScheduleRepository
     * @param CurriculumRepositoryContract         $curriculumRepository
     * @param StudySessionRepositoryContract       $studySessionRepository
     * @param FileUploadHandler                    $fileUploadHandler
     * @param DataVersionStudentRepositoryContract $dataVersionStudentRepository
     * @param RoomRepositoryContract               $roomRepository
     * @param RabbitMQServiceContract              $rabbitMQService
     */
    public function __construct (ModuleClassRepositoryContract        $moduleClassRepository,
                                 StudentRepositoryContract            $studentRepository,
                                 ModuleRepositoryContract             $moduleRepository,
                                 ClassRepositoryContract              $classRepository,
                                 ScheduleRepositoryContract           $scheduleRepository,
                                 ExamScheduleRepositoryContract       $examScheduleRepository,
                                 CurriculumRepositoryContract         $curriculumRepository,
                                 StudySessionRepositoryContract       $studySessionRepository,
                                 FileUploadHandler                    $fileUploadHandler,
                                 DataVersionStudentRepositoryContract $dataVersionStudentRepository,
                                 RoomRepositoryContract               $roomRepository,
                                 RabbitMQServiceContract              $rabbitMQService)
    {
        $this->moduleClassRepository        = $moduleClassRepository;
        $this->studentRepository            = $studentRepository;
        $this->moduleRepository             = $moduleRepository;
        $this->classRepository              = $classRepository;
        $this->scheduleRepository           = $scheduleRepository;
        $this->examScheduleRepository       = $examScheduleRepository;
        $this->curriculumRepository         = $curriculumRepository;
        $this->studySessionRepository       = $studySessionRepository;
        $this->fileUploadHandler            = $fileUploadHandler;
        $this->dataVersionStudentRepository = $dataVersionStudentRepository;
        $this->roomRepository               = $roomRepository;
        $this->rabbitMQService              = $rabbitMQService;
    }

    /**
     * @throws Exception
     */
    public function importRollCallFile (array $inputs)
    {
        $this->excelService = app()->make('excel_roll_call');
        $this->__handleFileUpload($inputs['file']);
        $idStudySession = $this->__readIdStudySessionByName($inputs['study_session']);;
        $moduleClassesWithFewStudents = $this->__getModuleClassesWithFewStudentsFromCache($inputs['id_department'],
                                                                                          $idStudySession);;
        $parameters = ['module_classes_with_few_students' => $moduleClassesWithFewStudents];;
        $data = $this->excelService->readData($this->fileUploadHandler->getFilePath(), $parameters);
        $this->__createAndUpdateRollCallData($data);
    }

    /**
     * @throws Exception
     */
    private function __handleFileUpload ($file)
    {
        $this->fileUploadHandler->handleFileUpload($file);
    }

    /**
     * @throws Exception
     */
    private function __getModuleClassesWithFewStudentsFromCache (string $idDepartment,
                                                                 string $idStudySession) : array
    {
        $key = "{$idStudySession}_{$idDepartment}_module_classes_with_few_students";;
        return Cache::get($key, []);
    }

    /**
     * @throws ImportDataFailedException
     */
    private function _checkExceptions2 ($module_classes_missing, $id_module_classes)
    {
        $id_module_classes_missing = $this->_getIDModuleClassesMissing($id_module_classes);
        $message                   = '';

        $module_classes_missing = array_merge($module_classes_missing, $id_module_classes_missing);
        if (!empty($module_classes_missing))
        {
            $message .= 'Cơ sở dữ liệu hiện tại không có một vài lớp học phần trong file excel cùng tên này:' .
                        PHP_EOL;;
            foreach ($module_classes_missing as $module_class)
            {
                $message .= $module_class . PHP_EOL;
            }
            //            GFunction::printFileImportException($file_name, $message);
            throw new ImportDataFailedException();
        }
    }

    private function _getIDModuleClassesMissing ($id_module_classes)
    {
        return $this->moduleClassRepository->getIDModuleClassesMissing($id_module_classes);
    }

    private function __createAndUpdateRollCallData (array $data)
    {
        $dataVersionStudent = collect($data['students'])->map(function ($item, $key)
        {
            return GFArray::onlyKeys($item, ['id' => 'id_student']);
        });

        DB::transaction(function () use ($data, $dataVersionStudent)
        {
            $this->__createManyStudents($data['students']);
            $this->__createManyModuleClassStudent($data['module_class_student']);
            $this->__updateDataVersionStudents($dataVersionStudent->all());
        }, 2);
    }

    private function __createManyStudents (array $students)
    {
        $students = collect($students)->unique('id')->sortBy('id')->all();
        $this->studentRepository->upsert($students);
    }

    private function __createManyModuleClassStudent (array $data)
    {
        foreach ($data as $idModuleClass => $idStudents)
        {
            try
            {
                $this->moduleClassRepository->insertPivot($idModuleClass, $idStudents, 'students');
            }
            catch (PDOException $error)
            {
                if ($error->getCode() == 23000 &&
                    $error->errorInfo[1] == 1062)
                {
                    continue;
                }
                throw $error;
            }
        }
    }

    private function __updateDataVersionStudents (array $dataVersionStudents)
    {
        $this->dataVersionStudentRepository->upsert($dataVersionStudents, [],
                                                    ['schedule' => DB::raw('schedule + 1')]);
    }

    /**
     * @throws Exception
     */
    public function importScheduleFile (array $inputs)
    {
        $this->excelService = app()->make('excel_schedule');
        $this->__handleFileUpload($inputs['file']);
        $idStudySession = $this->__readIdStudySessionByName($inputs['study_session']);

//        $data      = $this->excelService->readData($this->fileUploadHandler->getFilePath(),
//                                                   ['id_study_session' => $idStudySession]);

        $data = $this->__readDataFromGoService($idStudySession);
        $idModules = $this->__getIdModulesFromModuleClasses($data['module_classes']);


        $modulesMissing = $this->__checkIfModulesMissing($idModules);
        if (!empty($modulesMissing))
        {
            $missingData = [];
            foreach ($data['module_classes'] as $moduleClass)
            {
                if (in_array($moduleClass['id_module'], $modulesMissing))
                {
                    $moduleName    = str_replace(str_replace($moduleClass['id_module'], '', $moduleClass['id']), '',
                                                 $moduleClass['name']);
                    $missingData[] = ['id' => $moduleClass['id_module'], 'name' => $moduleName];
                }
            }

            $messages = ['modulesMissing' => $missingData];
            throw new DatabaseConflictException(json_encode($messages), 409);
        }

        $roomIds = array_unique(array_column($data['schedules'], 'id_room'));
        $this->__createOrUpdateRooms(collect($roomIds)->map(function (string $roomId, string $key)
        {
            return ['id' => $roomId, 'name' => $roomId];
        })->toArray());

        $this->__createAndUpdateScheduleData($data);
        $this->__updateModuleClassesWithFewStudentsToCache($data['module_classes'], $inputs['id_department'],
                                                           $idStudySession);
    }

    private function __readDataFromGoService (int $idStudySession) : array
    {
        $this->rabbitMQService->initConnection();
        $this->rabbitMQService->initChannel();
        $this->rabbitMQService->declareQueue('rpc_read_excel_file');
        $temporaryQueue = $this->rabbitMQService->declareAndReturnTemporaryQueue('', false, false, true, false);
        $this->rabbitMQService->basicConsume($temporaryQueue, '', false, true, false, false,
                                             [$this->rabbitMQService, 'receiveRPCResponse']);
        $message = new AMQPMessage(
            json_encode(['file_name'        => $this->fileUploadHandler->getNewFileName(),
                         'study_session_id' => $idStudySession,]),
            ['content-type'   => 'application/json',
             'reply_to'       => $temporaryQueue,]
        );

        $this->rabbitMQService->sendRPCRequest($message, '', 'rpc_read_excel_file');
        $this->rabbitMQService->waitForRPCResponse();
        return json_decode($this->rabbitMQService->getRpcResponse(), true);
    }

    private function __createOrUpdateRooms (array $roomIds)
    {
        $this->roomRepository->upsert($roomIds, ['id']);
    }

    private function __readIdStudySessionByName (string $name)
    {
        return $this->studySessionRepository->find(['id'], [['name', '=', $name]])[0]->id;
    }

    private function __getIdModulesFromModuleClasses (array $moduleClasses) : array
    {
        return collect($moduleClasses)->pluck('id_module')->unique()->values()->all();
    }

    private function __getIdModulesMissing ($idModules) : array
    {
        $idModelsAvailable = $this->__readIdModulesByIdModules($idModules)->all();
        return collect($idModules)->diff($idModelsAvailable)->all();
    }

    private function __readIdModulesByIdModules (array $idModules)
    {
        return $this->moduleRepository->pluckByIds($idModules, ['id']);
    }

    private function __createAndUpdateScheduleData ($data)
    {
        DB::transaction(function () use ($data)
        {
            $this->_createManyModuleClasses($data['module_classes']);
            $this->_createManySchedules($data['schedules']);
        }, 2);
    }

    private function _createManyModuleClasses ($module_classes)
    {
        $this->moduleClassRepository->upsert($module_classes, [], ['deleted_at' => null]);
    }

    private function _createManySchedules ($schedules)
    {
        $this->scheduleRepository->insertMultiple($schedules);
    }

    /**
     * @throws DatabaseConflictException
     */
    private function __checkIfModulesMissing ($idModules)
    {
        $modulesMissing = $this->__getIdModulesMissing($idModules);
        return $modulesMissing;
//        if (!empty($modulesMissing))
//        {
//            $messages = ['modulesMissing' => $modulesMissing];
//            throw new DatabaseConflictException(json_encode($messages), 409);
//        }
    }

    private function __updateModuleClassesWithFewStudentsToCache (array  $moduleClasses,
                                                                  string $idDepartment,
                                                                  string $idStudySession)
    {
        $moduleClassesWithFewStudents = collect($moduleClasses)->filter(function ($item, $key)
        {
            return $item['number_reality'] <= self::MINIMUM_NUMBER_OF_STUDENTS;
        });

        if (!empty($moduleClassesWithFewStudents))
        {
            $idModuleClassesWithFewStudents = $moduleClassesWithFewStudents->pluck('id', 'name')
                                                                           ->all();
            CacheModuleClassesWithFewStudents::dispatch($idModuleClassesWithFewStudents,
                                                        $idStudySession,
                                                        $idDepartment);
        }
    }

    /**
     * @throws BindingResolutionException
     * @throws Exception
     */
    public function importExamScheduleFile (array $inputs)
    {
        $this->excelService = app()->make('excel_exam_schedule');
        $this->__handleFileUpload($inputs['file']);
        $data = $this->excelService->readData($this->fileUploadHandler->getFilePath());
        $this->__createAndUpdateExamScheduleData($data);
    }

    private function __createAndUpdateExamScheduleData ($data)
    {
        DB::transaction(function () use ($data)
        {
            $this->_createManyExamSchedules($data['exam_schedules']);
        }, 2);
    }

    private function _createManyExamSchedules ($examSchedules)
    {
        $this->examScheduleRepository->insertMultiple($examSchedules);
    }

    /**
     * @throws BindingResolutionException
     * @throws Exception
     */
    public function importCurriculumFile (array $inputs)
    {
        $this->excelService = app()->make('excel_curriculum');
        $data               = $this->excelService->readData();
        $this->_createAndUpdateData4($data);
    }

    private function _createAndUpdateData4 ($data)
    {
        DB::transaction(function () use ($data)
        {
            $id_curriculum = $this->_createCurriculum($data['curriculum']);
            $this->_createModules($data['modules']);
            $this->_createManyCurriculumModule($id_curriculum, $data['id_modules']);
        }, 2);
    }

    private function _createCurriculum ($curriculum)
    {
        return $this->curriculumRepository->insertGetId($curriculum);
    }

    private function _createModules ($modules)
    {
        $this->moduleRepository->upsert($modules);
    }

    private function _createManyCurriculumModule ($id_curriculum, $id_modules)
    {
        $this->curriculumRepository->insertPivot($id_curriculum, $id_modules, 'modules');
    }
}
