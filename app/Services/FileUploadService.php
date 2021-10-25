<?php

namespace App\Services;

use App\BusinessClasses\FileUploadHandler;
use App\Exceptions\ImportDataFailedException;
use App\Helpers\SharedFunctions;
use App\Imports\Handler\ExcelDataHandler1;
use App\Imports\Handler\ExcelDataHandler2;
use App\Imports\Reader\ExcelFileReader1;
use App\Imports\Reader\ExcelFileReader2;
use App\Repositories\Contracts\AccountRepositoryContract;
use App\Repositories\Contracts\ClassRepositoryContract;
use App\Repositories\Contracts\DataVersionStudentRepositoryContract;
use App\Repositories\Contracts\ModuleClassRepositoryContract;
use App\Repositories\Contracts\ModuleRepositoryContract;
use App\Repositories\Contracts\ScheduleRepositoryContract;
use App\Repositories\Contracts\StudentRepositoryContract;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use PDOException;

class FileUploadService implements Contracts\FileUploadServiceContract
{
    private FileUploadHandler $fileUploadHandler;
    private ExcelFileReader1 $excelFileReader1;
    private ExcelDataHandler1 $excelDataHandler1;
    private ExcelFileReader2 $excelFileReader2;
    private ExcelDataHandler2 $excelDataHandler2;
    private DataVersionStudentRepositoryContract $dataVersionStudentRepository;
    private ModuleClassRepositoryContract $moduleClassRepository;
    private StudentRepositoryContract $studentRepository;
    private AccountRepositoryContract $accountRepository;
    private ModuleRepositoryContract $moduleRepository;
    private ClassRepositoryContract $classRepository;
    private ScheduleRepositoryContract $scheduleRepository;

    /**
     * @param FileUploadHandler $fileUploadHandler
     * @param ExcelFileReader1 $excelFileReader1
     * @param ExcelDataHandler1 $excelDataHandler1
     * @param ExcelFileReader2 $excelFileReader2
     * @param ExcelDataHandler2 $excelDataHandler2
     * @param DataVersionStudentRepositoryContract $dataVersionStudentRepository
     * @param ModuleClassRepositoryContract $moduleClassRepository
     * @param StudentRepositoryContract $studentRepository
     * @param AccountRepositoryContract $accountRepository
     * @param ModuleRepositoryContract $moduleRepository
     * @param ClassRepositoryContract $classRepository
     * @param ScheduleRepositoryContract $scheduleRepository
     */
    public function __construct (FileUploadHandler                    $fileUploadHandler,
                                 ExcelFileReader1                     $excelFileReader1,
                                 ExcelDataHandler1                    $excelDataHandler1,
                                 ExcelFileReader2                     $excelFileReader2,
                                 ExcelDataHandler2                    $excelDataHandler2,
                                 DataVersionStudentRepositoryContract $dataVersionStudentRepository,
                                 ModuleClassRepositoryContract        $moduleClassRepository,
                                 StudentRepositoryContract            $studentRepository,
                                 AccountRepositoryContract            $accountRepository,
                                 ModuleRepositoryContract             $moduleRepository,
                                 ClassRepositoryContract              $classRepository,
                                 ScheduleRepositoryContract           $scheduleRepository)
    {
        $this->fileUploadHandler            = $fileUploadHandler;
        $this->excelFileReader1             = $excelFileReader1;
        $this->excelDataHandler1            = $excelDataHandler1;
        $this->excelFileReader2             = $excelFileReader2;
        $this->excelDataHandler2            = $excelDataHandler2;
        $this->dataVersionStudentRepository = $dataVersionStudentRepository;
        $this->moduleClassRepository        = $moduleClassRepository;
        $this->scheduleRepository           = $scheduleRepository;
        $this->studentRepository            = $studentRepository;
        $this->accountRepository            = $accountRepository;
        $this->moduleRepository             = $moduleRepository;
        $this->classRepository              = $classRepository;
    }

    /**
     * @param $input
     * @throws Exception
     */
    public function importRollCallFile ($input)
    {
        $this->fileUploadHandler->handleFileUpload($input['file']);
        $data = $this->_readData($input['id_department']);
        $this->_checkExceptions2($data['module_classes_missing'], $data['id_module_classes']);
        $new_id_students = $this->_getIDStudentsMissing($data['id_students']);
        $data            = $this->_handleData($data, $input['id_training_type'], $new_id_students);
        $this->_createAndUpdateData2($data, $new_id_students);
    }

    /**
     * @throws Exception
     */
    private function _readData ($id_department) : array
    {
        $special_module_classes = Cache::get($id_department . '_special_module_classes') ??
                                  Cache::get($id_department . '_special_module_classes_backup');
        return $this->excelFileReader1->readData($this->fileUploadHandler->getNewFileName(),
                                                 $special_module_classes);
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
            SharedFunctions::printFileImportException($file_name, $message);
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
        return $this->excelDataHandler1->handleData($formatted_data, $id_training_type,
                                                    $new_id_students, $academic_years);
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
            $this->_createAccounts($data['accounts']);
            $this->_bindIDAccountsToStudents($new_id_students);
            $new_id_accounts = $this->_getNewIDAccounts($new_id_students);
            $this->_createRolesAccountsForStudents($new_id_accounts);
            $this->_createDataVersionStudents($data['data_version_students']);
            $this->_createParticipates($data['participates']);
            $this->_updateDataVersionStudents($data['old_id_students']);
        }, 2);
    }

    private function _createStudents ($data)
    {
        $this->studentRepository->insertMultiple($data);
    }

    private function _createAccounts ($data)
    {
        $this->accountRepository->insertMultiple($data);
    }

    private function _createRolesAccountsForStudents ($data)
    {
        foreach ($data as $id_account)
        {
            $this->accountRepository->insertPivotMultiple($id_account, [11]);
        }
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
                if ($error->getCode() == 23000
                    && $error->errorInfo[1] == 1062)
                {
                    continue;
                }
                throw $error;
            }
        }
    }

    private function _createDataVersionStudents ($data)
    {
        $this->dataVersionStudentRepository->insertMultiple($data);
    }

    private function _createParticipates ($data)
    {
        foreach ($data as $id_module_class => $id_students)
        {
            try
            {
                $this->moduleClassRepository->insertPivotMultiple($id_module_class, $id_students);
            }
            catch (PDOException $error)
            {
                if ($error->getCode() == 23000
                    && $error->errorInfo[1] == 1062)
                {
                    continue;
                }
                throw $error;
            }
        }
    }

    private function _updateDataVersionStudents ($id_students)
    {
        $this->dataVersionStudentRepository->updateMultiple($id_students, 'schedule');
    }

    private function _bindIDAccountsToStudents ($id_students)
    {
        $this->studentRepository->updateMultiple($id_students);
    }

    private function _getNewIDAccounts ($id_students)
    {
        return $this->studentRepository->getIDAccounts($id_students);
    }

    /**
     * @throws Exception
     */
    public function importScheduleFile ($input)
    {
        $this->fileUploadHandler->handleFileUpload($input['file']);
        $data = $this->excelFileReader2->readData($this->fileUploadHandler->getNewFileName(),
                                                  $input['id_study_session']);

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
        $this->moduleClassRepository->insertMultiple($module_classes);
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

            SharedFunctions::printFileImportException($file_name, $message);
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
}