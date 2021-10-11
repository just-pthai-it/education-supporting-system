<?php

namespace App\Services;

use App\Repositories\Contracts\DepartmentRepositoryContract;
use App\Repositories\Contracts\FacultyRepositoryContract;
use App\Repositories\Contracts\OtherDepartmentRepositoryContract;
use App\Repositories\Contracts\TeacherRepositoryContract;
use App\Exceptions\InvalidAccountException;
use App\Services\Contracts\LoginServiceContract;

class LoginService implements LoginServiceContract
{
    private OtherDepartmentRepositoryContract $otherDepartmentDepository;
    private DepartmentRepositoryContract $departmentDepository;
    private TeacherRepositoryContract $teacherDepository;
    private FacultyRepositoryContract $facultyDepository;

    /**
     * @param OtherDepartmentRepositoryContract $otherDepartmentDepository
     * @param DepartmentRepositoryContract $departmentDepository
     * @param TeacherRepositoryContract $teacherDepository
     * @param FacultyRepositoryContract $facultyDepository
     */
    public function __construct (OtherDepartmentRepositoryContract $otherDepartmentDepository,
                                 DepartmentRepositoryContract      $departmentDepository,
                                 TeacherRepositoryContract         $teacherDepository,
                                 FacultyRepositoryContract         $facultyDepository)
    {
        $this->otherDepartmentDepository = $otherDepartmentDepository;
        $this->departmentDepository      = $departmentDepository;
        $this->teacherDepository         = $teacherDepository;
        $this->facultyDepository         = $facultyDepository;
    }

    /**
     * @throws InvalidAccountException
     */
    public function login ($username, $password) : array
    {
        $token = $this->_authenticate($username, $password);
        $data  = $this->_getAccountOwnerInfo(auth()->user()->id, auth()->user()->permission);

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
    protected function _getAccountOwnerInfo ($id_account, $permission)
    {
        switch ($permission)
        {
            case 1:
                $data       = $this->teacherDepository->get($id_account);
                $data->name = 'Gv.' . $data->teacher_name;
                break;

            case 2:
                $data       = $this->departmentDepository->get($id_account);
                $data->name = 'Bộ môn ' . $data->department_name;
                break;
            case 3:
                $data       = $this->facultyDepository->get($id_account);
                $data->name = 'Khoa ' . $data->faculty_name;
                break;

            case 4:
                $data       = $this->otherDepartmentDepository->get($id_account);
                $data->name = 'Phòng ' . $data->other_department_name;
                break;

            default:
                throw new InvalidAccountException();

        }

        return $data;
    }

}
