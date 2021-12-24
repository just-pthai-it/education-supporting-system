<?php

namespace App\Services;

use App\Exceptions\InvalidAccountException;
use App\Repositories\Contracts\DepartmentRepositoryContract;
use App\Repositories\Contracts\FacultyRepositoryContract;
use App\Repositories\Contracts\PermissionRepositoryContract;
use App\Repositories\Contracts\OtherDepartmentRepositoryContract;
use App\Repositories\Contracts\TeacherRepositoryContract;

class AuthService implements Contracts\AuthServiceContract
{
    private OtherDepartmentRepositoryContract $otherDepartmentDepository;
    private DepartmentRepositoryContract $departmentDepository;
    private TeacherRepositoryContract $teacherDepository;
    private FacultyRepositoryContract $facultyDepository;
    private PermissionRepositoryContract $permissionRepository;

    /**
     * @param OtherDepartmentRepositoryContract $otherDepartmentDepository
     * @param DepartmentRepositoryContract      $departmentDepository
     * @param TeacherRepositoryContract         $teacherDepository
     * @param FacultyRepositoryContract         $facultyDepository
     * @param PermissionRepositoryContract      $permissionRepository
     */
    public function __construct (OtherDepartmentRepositoryContract $otherDepartmentDepository,
                                 DepartmentRepositoryContract      $departmentDepository,
                                 TeacherRepositoryContract         $teacherDepository,
                                 FacultyRepositoryContract         $facultyDepository,
                                 PermissionRepositoryContract      $permissionRepository)
    {
        $this->otherDepartmentDepository = $otherDepartmentDepository;
        $this->departmentDepository      = $departmentDepository;
        $this->teacherDepository         = $teacherDepository;
        $this->facultyDepository         = $facultyDepository;
        $this->permissionRepository      = $permissionRepository;
    }


    /**
     * @throws InvalidAccountException
     */
    public function login ($username, $password) : array
    {
        $token = $this->_authenticate($username, $password);
        $data  = $this->getUserInfo();
        $local_data = array('currentTerm' => \config('app.current_term'));

        return [
            'local_data' => $local_data,
            'data'  => $data,
            'token' => $token,
        ];
    }

    /**
     * @throws InvalidAccountException
     */
    private function _authenticate ($username_email, $password)
    {
        $credential = [
            'password' => $password,
        ];

        if (strpos($username_email, '@') !== false)
        {
            $credential['email'] = $username_email;
        }
        else
        {
            $credential['username'] = $username_email;
        }


        if (!$token = auth()->attempt($credential))
        {
            throw new InvalidAccountException();
        }

        return $token;
    }

    /**
     * @throws InvalidAccountException
     */
    public function getUserInfo ()
    {
        $permissions = $this->_getAccountPermissions();
        switch (auth()->user()->id_role)
        {
            //            case 'AD':
            //                $data = collect([]);
            //                $data->name = 'ADMIN';
            //                break;

            case 5;
                $data       = $this->otherDepartmentDepository->get(auth()->user()->id_user);
                $data->name = 'Phòng ' . $data->name;
                break;

            case 4:
                $data       = $this->facultyDepository->get(auth()->user()->id_user);
                $data->name = 'Khoa ' . $data->name;
                break;

            case 2:
            case 3:
                $data = $this->teacherDepository->findById(auth()->user()->id_user);
                break;

            default:
                throw new InvalidAccountException();
        }

        $data->uuid_account = auth()->user()->uuid;
        $data->permissions  = $permissions;
        return $data;
    }

    private function _getAccountPermissions ()
    {
        return $this->permissionRepository->findByIdRole(auth()->user()->id_role);
    }

    public function logout ()
    {
        auth()->logout();
    }
}
