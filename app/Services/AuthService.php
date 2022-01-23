<?php

namespace App\Services;

use App\Exceptions\InvalidAccountException;
use App\Repositories\Contracts\RoleRepositoryContract;
use App\Repositories\Contracts\DepartmentRepositoryContract;
use App\Repositories\Contracts\FacultyRepositoryContract;
use App\Repositories\Contracts\PermissionRepositoryContract;
use App\Repositories\Contracts\OtherDepartmentRepositoryContract;
use App\Repositories\Contracts\TeacherRepositoryContract;

class AuthService implements Contracts\AuthServiceContract
{
    private OtherDepartmentRepositoryContract $otherDepartmentDepository;
    private TeacherRepositoryContract $teacherDepository;
    private RoleRepositoryContract $roleRepository;

    /**
     * @param OtherDepartmentRepositoryContract $otherDepartmentDepository
     * @param TeacherRepositoryContract         $teacherDepository
     * @param RoleRepositoryContract            $roleRepository
     */
    public function __construct (OtherDepartmentRepositoryContract $otherDepartmentDepository,
                                 TeacherRepositoryContract         $teacherDepository,
                                 RoleRepositoryContract            $roleRepository)
    {
        $this->otherDepartmentDepository = $otherDepartmentDepository;
        $this->teacherDepository         = $teacherDepository;
        $this->roleRepository            = $roleRepository;
    }

    /**
     * @throws InvalidAccountException
     */
    public function login ($username, $password) : array
    {
        $token      = $this->_authenticate($username, $password);
        $data       = $this->getUserInfo();
        $local_data = ['currentTerm' => \config('app.current_term')];

        return [
            'local_data' => $local_data,
            'data'       => $data,
            'token'      => $token,
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
            case 5;
                $data = $this->otherDepartmentDepository->findByIds(auth()->user()->id_user, [], [],
                                                                    [['withUuid']],
                                                                    [['makeVisible', ['uuid']]]);
                break;

            case 4:
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
        return $this->roleRepository->findPermissionsByIdRole(auth()->user()->id_role);
    }

    public function logout ()
    {
        auth()->logout();
    }
}
