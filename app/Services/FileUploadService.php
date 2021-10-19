<?php

namespace App\Services;

use App\BusinessClasses\FileUploadHandler;
use App\Exceptions\ImportDataFailedException;
use App\Helpers\SharedFunctions;
use App\Imports\Handler\ExcelDataHandler1;
use App\Imports\Reader\ExcelFileReader1;
use App\Repositories\Contracts\AccountRepositoryContract;
use App\Repositories\Contracts\ClassRepositoryContract;
use App\Repositories\Contracts\DataVersionStudentRepositoryContract;
use App\Repositories\Contracts\ModuleClassRepositoryContract;
use App\Repositories\Contracts\ModuleRepositoryContract;
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
    private DataVersionStudentRepositoryContract $dataVersionStudentRepository;
    private ModuleClassRepositoryContract $moduleClassRepository;
    private StudentRepositoryContract $studentRepository;
    private AccountRepositoryContract $accountRepository;
    private ModuleRepositoryContract $moduleRepository;
    private ClassRepositoryContract $classRepository;

    /**
     * @param FileUploadHandler $fileUploadHandler
     * @param ExcelFileReader1 $excelFileReader1
     * @param ExcelDataHandler1 $excelDataHandler1
     * @param DataVersionStudentRepositoryContract $dataVersionStudentRepository
     * @param ModuleClassRepositoryContract $moduleClassRepository
     * @param StudentRepositoryContract $studentRepository
     * @param AccountRepositoryContract $accountRepository
     * @param ModuleRepositoryContract $moduleRepository
     * @param ClassRepositoryContract $classRepository
     */
    public function __construct (FileUploadHandler                    $fileUploadHandler,
                                 ExcelFileReader1                     $excelFileReader1,
                                 ExcelDataHandler1                    $excelDataHandler1,
                                 DataVersionStudentRepositoryContract $dataVersionStudentRepository,
                                 ModuleClassRepositoryContract        $moduleClassRepository,
                                 StudentRepositoryContract            $studentRepository,
                                 AccountRepositoryContract            $accountRepository,
                                 ModuleRepositoryContract             $moduleRepository,
                                 ClassRepositoryContract              $classRepository)
    {
        $this->fileUploadHandler            = $fileUploadHandler;
        $this->excelFileReader1             = $excelFileReader1;
        $this->excelDataHandler1            = $excelDataHandler1;
        $this->dataVersionStudentRepository = $dataVersionStudentRepository;
        $this->moduleClassRepository        = $moduleClassRepository;
        $this->studentRepository            = $studentRepository;
        $this->accountRepository            = $accountRepository;
        $this->moduleRepository             = $moduleRepository;
        $this->classRepository              = $classRepository;
    }

    /**
     * @throws Exception
     */
    public function importRollCallFile ($file, $id_training_type)
    {
        $this->fileUploadHandler->handleFileUpload($file);
        $data = $this->_readData();
        $this->_checkExceptions($data['module_exception'], $data['module_classes']);
        $new_id_students = $this->_getIDStudentsNotInDatabase($data['id_students']);
        $data            = $this->_handleData($data, $id_training_type, $new_id_students);
        $this->_importAndUpdateData($data, $new_id_students);
    }

    /**
     * @throws Exception
     */
    private function _readData () : array
    {
        $modules = Cache::get('modules') ?? Cache::get('modules_backup');
        return $this->excelFileReader1->readData($this->fileUploadHandler->getNewFileName(), $modules);
    }

    /**
     * @throws ImportDataFailedException
     */
    private function _checkExceptions ($module_exceptions, $id_module_classes)
    {
        $new_id_module_classes = $this->_getIDModuleClassesNotInDatabase($id_module_classes);
        $file_name             = $this->fileUploadHandler->getOldFileName() . '.txt';
        $message               = '';

        if (!empty($module_exceptions))
        {
            $message .= 'Cơ sở dữ liệu hiện tại không có một vài mã học phần trong file excel cùng tên này:' . PHP_EOL;;
            foreach ($module_exceptions as $id_module_class)
            {
                $message .= $id_module_class . PHP_EOL;
            }
        }
        if (!empty($new_id_module_classes))
        {
            $message .= 'Cơ sở dữ liệu hiện tại không có một vài mã lớp học phần trong file excel cùng tên này:' . PHP_EOL;;
            foreach ($new_id_module_classes as $id_module_class)
            {
                $message .= $id_module_class . PHP_EOL;
            }
        }

        if ($message != '')
        {
            SharedFunctions::printFileImportException($file_name, $message);
            throw new ImportDataFailedException();
        }
    }

    private function _getIDModuleClassesNotInDatabase ($id_module_classes)
    {
        return $this->moduleClassRepository->getIDModuleClassesNotInDatabase($id_module_classes);
    }

    private function _handleData ($formatted_data, $id_training_type, $new_id_students) : array
    {
        $academic_years = Cache::get('academic_years') ?? Cache::get('academic_years_backup');
        return $this->excelDataHandler1->handleData($formatted_data, $id_training_type,
                                                    $new_id_students, $academic_years);
    }

    private function _getIDStudentsNotInDatabase ($id_students)
    {
        return $this->studentRepository->getIDStudentsNotInDatabase($id_students);
    }

    private function _importAndUpdateData ($data, $new_id_students)
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
}