<?php

namespace App\Services;

use App\BusinessClasses\FileUploadHandler;
use App\Exceptions\ImportDataFailedException;
use App\Helpers\GFunction;
use App\Services\Contracts\ExcelServiceContract;
use App\Repositories\Contracts\ClassRepositoryContract;
use App\Repositories\Contracts\TeacherRepositoryContract;
use App\Repositories\Contracts\CurriculumRepositoryContract;
use App\Repositories\Contracts\ModuleClassRepositoryContract;
use App\Repositories\Contracts\ModuleRepositoryContract;
use App\Repositories\Contracts\ScheduleRepositoryContract;
use App\Repositories\Contracts\StudentRepositoryContract;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use PDOException;
use Illuminate\Contracts\Container\BindingResolutionException;
use App\Repositories\Contracts\ExamScheduleRepositoryContract;
use App\Repositories\Contracts\StudySessionRepositoryContract;

class ResourceService implements Contracts\ResourceServiceContract
{
    private FileUploadHandler $fileUploadHandler;
    private ModuleClassRepositoryContract $moduleClassRepository;
    private StudentRepositoryContract $studentRepository;
    private TeacherRepositoryContract $teacherRepository;
    private ModuleRepositoryContract $moduleRepository;
    private ClassRepositoryContract $classRepository;
    private ScheduleRepositoryContract $scheduleRepository;
    private ExamScheduleRepositoryContract $examScheduleRepository;
    private CurriculumRepositoryContract $curriculumRepository;
    private StudySessionRepositoryContract $studySessionRepository;
    private ExcelServiceContract $excelService;

    /**
     * @param FileUploadHandler              $fileUploadHandler
     * @param ModuleClassRepositoryContract  $moduleClassRepository
     * @param StudentRepositoryContract      $studentRepository
     * @param TeacherRepositoryContract      $teacherRepository
     * @param ModuleRepositoryContract       $moduleRepository
     * @param ClassRepositoryContract        $classRepository
     * @param ScheduleRepositoryContract     $scheduleRepository
     * @param ExamScheduleRepositoryContract $examScheduleRepository
     * @param CurriculumRepositoryContract   $curriculumRepository
     * @param StudySessionRepositoryContract $studySessionRepository
     */
    public function __construct (FileUploadHandler              $fileUploadHandler,
                                 ModuleClassRepositoryContract  $moduleClassRepository,
                                 StudentRepositoryContract      $studentRepository,
                                 TeacherRepositoryContract      $teacherRepository,
                                 ModuleRepositoryContract       $moduleRepository,
                                 ClassRepositoryContract        $classRepository,
                                 ScheduleRepositoryContract     $scheduleRepository,
                                 ExamScheduleRepositoryContract $examScheduleRepository,
                                 CurriculumRepositoryContract   $curriculumRepository,
                                 StudySessionRepositoryContract $studySessionRepository)
    {
        $this->fileUploadHandler      = $fileUploadHandler;
        $this->moduleClassRepository  = $moduleClassRepository;
        $this->studentRepository      = $studentRepository;
        $this->teacherRepository      = $teacherRepository;
        $this->moduleRepository       = $moduleRepository;
        $this->classRepository        = $classRepository;
        $this->scheduleRepository     = $scheduleRepository;
        $this->examScheduleRepository = $examScheduleRepository;
        $this->curriculumRepository   = $curriculumRepository;
        $this->studySessionRepository = $studySessionRepository;
    }

    /**
     * @throws Exception
     */
    public function importRollCallFile ($input)
    {
        $this->excelService = app()->make('excel_roll_call');
        $this->fileUploadHandler->handleFileUpload($input['file']);
        $data = $this->_readData($input['id_department']);
        $this->_checkExceptions2($data['module_classes_missing'], $data['id_module_classes']);
        $id_students_missing = $this->_getIDStudentsMissing($data['id_students']);
        $data                = $this->_handleData($data, $input['id_training_type'],
                                                  $id_students_missing);
        $this->_createAndUpdateData2($data, $id_students_missing);
    }

    /**
     * @throws Exception
     */
    private function _readData ($id_department) : array
    {
        $special_module_classes = Cache::get($id_department . '_special_module_classes') ??
                                  Cache::get($id_department . '_special_module_classes_backup');
        $this->excelService->setParameters($special_module_classes, null, null, null);
        return $this->excelService->readData($this->fileUploadHandler->getNewFileName());
    }

    /**
     * @throws ImportDataFailedException
     */
    private function _checkExceptions2 ($module_classes_missing, $id_module_classes)
    {
        $id_module_classes_missing = $this->_getIDModuleClassesMissing($id_module_classes);
        $file_name                 = $this->fileUploadHandler->getOldFileName() . '.txt';
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
            GFunction::printFileImportException($file_name, $message);
            throw new ImportDataFailedException();
        }
    }

    private function _getIDModuleClassesMissing ($id_module_classes)
    {
        return $this->moduleClassRepository->getIDModuleClassesMissing($id_module_classes);
    }

    private function _handleData ($formatted_data, $id_training_type, $new_id_students) : array
    {
        $academic_years = Cache::get('academic_years') ?? Cache::get('academic_years_backup');
        $this->excelService->setParameters(null, $new_id_students,
                                           $academic_years, $id_training_type);
        return $this->excelService->handleData($formatted_data);
    }

    private function _getIDStudentsMissing ($id_students)
    {
        return $this->studentRepository->getIDStudentsMissing($id_students);
    }

    private function _createAndUpdateData2 ($data, $new_id_students)
    {
        DB::transaction(function () use ($data, $new_id_students)
        {
            $this->_createClasses($data['classes']);
            $this->_createStudents($data['students']);
            $this->_createParticipates($data['participates']);
            $this->_updateDataVersionStudents($data['available_id_students']);
        }, 2);
    }

    private function _createStudents ($data)
    {
        $this->studentRepository->insertMultiple($data);
    }

    private function _createClasses ($data)
    {
        foreach ($data as $class)
        {
            try
            {
                $this->classRepository->insert($class);
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

    private function _createParticipates ($data)
    {
        foreach ($data as $id_module_class => $id_students)
        {
            try
            {
                $this->moduleClassRepository->insertPivot($id_module_class, $id_students,
                                                          'students');
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

    private function _updateDataVersionStudents ($id_students)
    {
        $this->studentRepository->updateMultiple2($id_students, 'schedule_data_version');
    }

    /**
     * @throws Exception
     */
    public function importScheduleFile ($input)
    {
        $this->excelService = app()->make('excel_schedule');
        $this->fileUploadHandler->handleFileUpload($input['file']);
        $idStudySession = $this->studySessionRepository->find(['id'],
                                                              [['name', '=', $input['study_session']]])[0]->id;;
        $data = $this->excelService->readData($this->fileUploadHandler->getNewFileName(),
                                              $idStudySession);

        $modules_missing = $this->_getIDModulesMissing($data['id_modules']);
        $this->_checkExceptions($modules_missing);
        $this->_createAndUpdateData($data);
        $this->_updateCacheData($data['special_module_classes'], $input['id_department'],
                                $input['id_study_session']);
    }

    private function _getIDModulesMissing ($id_modules)
    {
        return $this->moduleRepository->getIDModulesMissing($id_modules);
    }

    private function _createAndUpdateData ($data)
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
     * @throws ImportDataFailedException
     */
    private function _checkExceptions ($modules_missing)
    {
        if (!empty($modules_missing))
        {
            $file_name = $this->fileUploadHandler->getOldFileName() . '.txt';
            $message   =
                'Cơ sở dữ liệu hiện tại không có một vài mã học phần tương ứng với các mã lớp học phần sau:' .
                PHP_EOL;
            foreach ($modules_missing as $modules)
            {
                $message .= $modules . PHP_EOL;
            }

            GFunction::printFileImportException($file_name, $message);
            throw new ImportDataFailedException();
        }
    }

    private function _updateCacheData ($module_classes, $id_department, $id_study_session)
    {
        $old_module_classes      = Cache::get($id_department . '_special_module_classes') ?? [];
        $recent_id_study_session = array_pop($old_module_classes);
        if ($recent_id_study_session != $id_study_session)
        {
            $old_module_classes = [];
        }
        Cache::forever($id_department . '_special_module_classes',
                       array_merge($old_module_classes, $module_classes));
    }

    /**
     * @throws BindingResolutionException
     * @throws Exception
     */
    public function importExamScheduleFile ($input)
    {
        $this->excelService = app()->make('excel_exam_schedule');
        $this->fileUploadHandler->handleFileUpload($input['file']);
        $teachers = $this->teacherRepository->pluck(['id', 'name'],
                                                    [['id_department', '=', $input['id_department']]])
                                            ->toArray();
        $data     = $this->excelService->readData($this->fileUploadHandler->getNewFileName(),
                                                  $teachers);
        $this->_createAndUpdateData3($data);
    }

    private function _createAndUpdateData3 ($data)
    {
        DB::transaction(function () use ($data)
        {
            $this->_createManyExamSchedules($data['exam_schedules']);
            $this->_createManyExamSchedulesTeachers($data['exam_schedules_teachers']);
        }, 2);
    }

    private function _createManyExamSchedules ($exam_schedules)
    {
        $this->examScheduleRepository->upsert($exam_schedules, [],
                                              ['id_module_class' => DB::raw('id_module_class')]);
    }

    private function _createManyExamSchedulesTeachers ($exam_schedules_teachers)
    {
        foreach ($exam_schedules_teachers as $id_module_class => $id_teachers)
        {
            $this->examScheduleRepository->syncPivot($id_module_class, $id_teachers, 'teachers');
        }
    }

    /**
     * @throws BindingResolutionException
     * @throws Exception
     */
    public function importCurriculumFile ($input)
    {
        $this->excelService = app()->make('excel_curriculum');
        $this->fileUploadHandler->handleFileUpload($input['file']);
        $data = $this->excelService->readData($this->fileUploadHandler->getNewFileName());
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