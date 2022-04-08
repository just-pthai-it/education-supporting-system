<?php

namespace App\Services;

use App\Exceptions\InvalidAccountException;
use App\Repositories\Contracts\RoleRepositoryContract;
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
        $token = $this->_authenticate($username, $password);
        $data  = $this->getUserInfo();

        return [
            'response' => $data,
            'token'    => $token,
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
        switch (auth()->user()->accountable_type)
        {
            case 'App\Models\OtherDepartment':
                $data = $this->otherDepartmentDepository->findByIds(auth()->user()->accountable_id,
                                                                    ['*'], [], [], []);
                break;

            case 'App\Models\Teacher':
                $data = $this->teacherDepository->findByIds(auth()->user()->accountable_id,
                                                            ['*'], [],
                                                            [['with', 'department:id,name,id_faculty',
                                                              'department.faculty:id,name']], []);
                break;

            default:
                throw new InvalidAccountException();
        }

        return $this->_completeUserData($data);
    }

    private function _completeUserData ($data)
    {
        $data->uuid_account = auth()->user()->uuid;
        $data->id_role      = auth()->user()->id_role;
        $data->email        = auth()->user()->email;
        $data->phone        = auth()->user()->phone;
        $data->permissions  = $this->_getAccountPermissions();

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
