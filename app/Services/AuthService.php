<?php

namespace App\Services;

use App\Models\Teacher;
use App\Http\Resources\UserData;
use Illuminate\Database\Eloquent\Collection;
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

    public function login ($username, $password)
    {
        $accessToken = $this->_authenticate($username, $password);
        if ($accessToken == '')
        {
            return response(['errors' => ['Invalid username, email or password']], 401);
        }

        $userInfo = $this->getUserInfo();
        if (is_null($userInfo))
        {
            return response(['errors' => ['Unknown this account owner']], 404);
        }

        return response(['data' => new UserData($userInfo)])
            ->header('Authorization', "Bearer {$accessToken}");
    }

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

        if (($accessToken = auth()->attempt($credential)) === false)
        {
            return '';
        }

        return $accessToken;
    }

    public function getUserInfo ()
    {
        $accountableId   = auth()->user()->accountable_id;
        $accountableType = auth()->user()->accountable_type;
        switch ($accountableType)
        {
            case 'App\Models\OtherDepartment':
                $conditions = [['id', '=', $accountableId]];;
                $data = $this->otherDepartmentDepository->find(['*'], $conditions);
                break;

            case Teacher::class:
                $conditions = [['id', '=', $accountableId]];;
                $scopes = [['with', 'department:id,name,id_faculty', 'department.faculty:id,name']];
                $data   = $this->teacherDepository->find(['*'], $conditions, [], [], $scopes);
                break;

            default:
                $data = new Collection();
        }

        if ($data->isEmpty())
        {
            return null;
        }

        return $this->_completeUserData($data[0]);
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
