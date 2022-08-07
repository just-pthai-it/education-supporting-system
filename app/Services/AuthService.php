<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\OtherDepartment;
use App\Http\Resources\UserResource;
use App\Exceptions\CustomAuthenticationException;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Contracts\RoleRepositoryContract;
use App\Repositories\Contracts\AdminRepositoryContract;
use App\Repositories\Contracts\StudentRepositoryContract;
use App\Repositories\Contracts\OtherDepartmentRepositoryContract;
use App\Repositories\Contracts\TeacherRepositoryContract;

class AuthService implements Contracts\AuthServiceContract
{
    private OtherDepartmentRepositoryContract $otherDepartmentDepository;
    private TeacherRepositoryContract         $teacherDepository;
    private StudentRepositoryContract         $studentRepository;
    private AdminRepositoryContract           $adminRepository;
    private RoleRepositoryContract            $roleRepository;

    /**
     * @param OtherDepartmentRepositoryContract $otherDepartmentDepository
     * @param TeacherRepositoryContract         $teacherDepository
     * @param RoleRepositoryContract            $roleRepository
     * @param StudentRepositoryContract         $studentRepository
     * @param AdminRepositoryContract           $adminRepository
     */
    public function __construct (OtherDepartmentRepositoryContract $otherDepartmentDepository,
                                 TeacherRepositoryContract         $teacherDepository,
                                 RoleRepositoryContract            $roleRepository,
                                 StudentRepositoryContract         $studentRepository,
                                 AdminRepositoryContract           $adminRepository)
    {
        $this->otherDepartmentDepository = $otherDepartmentDepository;
        $this->teacherDepository         = $teacherDepository;
        $this->roleRepository            = $roleRepository;
        $this->studentRepository         = $studentRepository;
        $this->adminRepository           = $adminRepository;
    }

    /**
     * @throws CustomAuthenticationException
     */
    public function login ($username, $password)
    {
        $accessToken = $this->_verifyCredentials($username, $password);
        $userInfo    = $this->getUserInfo();
        if (is_null($userInfo))
        {
            $messages = json_encode(['Unknown this account owner']);
            throw new CustomAuthenticationException($messages, 404);
        }

        return response(['data' => new UserResource($userInfo)])
            ->header('Authorization', "Bearer {$accessToken}");
    }

    /**
     * @throws CustomAuthenticationException
     */
    private function _verifyCredentials ($usernameOrEmail, $password) : string
    {
        if (strpos($usernameOrEmail, '@') !== false)
        {
            $credentials['email'] = $usernameOrEmail;
        }
        else
        {
            $credentials['username'] = $usernameOrEmail;
        }

        $credentials['password'] = $password;

        if (($accessToken = auth()->attempt($credentials)) === false)
        {
            $messages = json_encode(['Invalid username, email or password']);
            throw new CustomAuthenticationException($messages, 401);
        }

        return $accessToken;
    }

    public function getUserInfo ()
    {
        $accountableId   = auth()->user()->accountable_id;
        $accountableType = auth()->user()->accountable_type;
        switch ($accountableType)
        {
            case Admin::class:
                $conditions = [['id', '=', $accountableId]];
                $data       = $this->adminRepository->find(['*'], $conditions);
                break;

            case OtherDepartment::class:
                $conditions = [['id', '=', $accountableId]];
                $data       = $this->otherDepartmentDepository->find(['*'], $conditions);
                break;

            case Teacher::class:
                $conditions = [['id', '=', $accountableId]];;
                $scopes = [['with', 'department:id,name,id_faculty', 'department.faculty:id,name']];
                $data   = $this->teacherDepository->find(['*'], $conditions, [], [], $scopes);
                break;

            case Student::class:
                $conditions = [['id', '=', $accountableId]];
                $data       = $this->studentRepository->find(['*'], $conditions);
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
        $data->tags         = auth()->user()->tags()->get(['taggable_id', 'taggable_type']);
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
