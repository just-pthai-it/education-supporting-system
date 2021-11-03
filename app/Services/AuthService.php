<?php

namespace App\Services;

use App\Exceptions\InvalidAccountException;
use App\Repositories\Contracts\AccountRepositoryContract;
use App\Repositories\Contracts\DepartmentRepositoryContract;
use App\Repositories\Contracts\FacultyRepositoryContract;
use App\Repositories\Contracts\OtherDepartmentRepositoryContract;
use App\Repositories\Contracts\TeacherRepositoryContract;

class AuthService implements Contracts\AuthServiceContract
{
    private OtherDepartmentRepositoryContract $otherDepartmentDepository;
    private DepartmentRepositoryContract $departmentDepository;
    private TeacherRepositoryContract $teacherDepository;
    private FacultyRepositoryContract $facultyDepository;
    private AccountRepositoryContract $accountRepository;

    /**
     * @param OtherDepartmentRepositoryContract $otherDepartmentDepository
     * @param DepartmentRepositoryContract      $departmentDepository
     * @param TeacherRepositoryContract         $teacherDepository
     * @param FacultyRepositoryContract         $facultyDepository
     * @param AccountRepositoryContract         $accountRepository
     */
    public function __construct (OtherDepartmentRepositoryContract $otherDepartmentDepository,
                                 DepartmentRepositoryContract      $departmentDepository,
                                 TeacherRepositoryContract         $teacherDepository,
                                 FacultyRepositoryContract         $facultyDepository,
                                 AccountRepositoryContract         $accountRepository)
    {
        $this->otherDepartmentDepository = $otherDepartmentDepository;
        $this->departmentDepository      = $departmentDepository;
        $this->teacherDepository         = $teacherDepository;
        $this->facultyDepository         = $facultyDepository;
        $this->accountRepository         = $accountRepository;
    }

    /**
     * @throws InvalidAccountException
     */
    public function login ($username, $password) : array
    {
        $token = $this->_authenticate($username, $password);
        $data  = $this->_getUserInfo(auth()->user()->id);

        return [
            'data'  => $data,
            'token' => $token,
        ];
    }

    /**
     * @throws InvalidAccountException
     */
    private function _authenticate ($username, $password)
    {
        $credential = [
            'username' => $username,
            'password' => $password
        ];

        if (!$token = auth()->attempt($credential))
        {
            throw new InvalidAccountException();
        }

        return $token;
    }

    /**
     * @throws InvalidAccountException
     */
    protected function _getUserInfo ($id_account)
    {
        $permissions = $this->_getAccountPermissions($id_account);
        switch ($this->_verifyAccountUser($permissions))
        {
            //            case 'admin':
            //                $data = collect([]);
            //                $data->name = 'ADMIN';
            //                break;

            case 'other_department.qldt':
            case 'other_department.qlph';
                $data       = $this->otherDepartmentDepository->get($id_account);
                $data->name = 'Phòng ' . $data->name;
                break;

            case 'faculty':
                $data       = $this->facultyDepository->get($id_account);
                $data->name = 'Khoa ' . $data->name;
                break;

            case 'department':
                $data       = $this->departmentDepository->get($id_account);
                $data->name = 'Bộ môn ' . $data->name;
                break;

            case 'teacher':
                $data       = $this->teacherDepository->get($id_account);
                $data->name = 'Gv ' . $data->name;
                break;

            default:
                throw new InvalidAccountException();
        }

        return $data;
    }

    private function _getAccountPermissions ($id_account)
    {
        return $this->accountRepository->getPermissions($id_account);
    }

    private function _verifyAccountUser ($permissions) : string
    {
        //        if (in_array(1, $permissions))
        //        {
        //            return 'admin';
        //        }
        if (in_array(2, $permissions) &&
            in_array(3, $permissions))
        {
            return 'other_department.qldt';
        }
        if (in_array(10, $permissions))
        {
            return 'other_department.qlph';
        }
        if (in_array(7, $permissions))
        {
            return 'faculty';
        }
        if (in_array(8, $permissions))
        {
            return 'department';
        }

        return 'teacher';
    }

    public function logout ()
    {
        auth()->logout();
    }
}