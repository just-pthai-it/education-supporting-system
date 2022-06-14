<?php

namespace App\Http\Resources;

use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\OtherDepartment;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray ($request) : array
    {
        switch ($request->user()->accountable_type)
        {
            case OtherDepartment::class:
                return [
                    'id'          => $this->id,
                    'name'        => $this->name,
                    'idRole'      => $this->id_role,
                    'address'     => $this->address,
                    'uuidAccount' => $this->uuid_account,
                    'permissions' => $this->permissions,
                ];

            case Teacher::class:
                return [
                    'id'                      => $this->id,
                    'name'                    => $this->name,
                    'idRole'                  => $this->id_role,
                    'isFemale'                => $this->is_female,
                    'birth'                   => $this->birth,
                    'email'                   => $this->email,
                    'phone'                   => $this->phone,
                    'universityTeacherDegree' => $this->university_teacher_degree,
                    'uuidAccount'             => $this->uuid_account,
                    'department'              => is_null($this->department) ? null : [
                        'id'   => $this->department->id,
                        'name' => $this->department->name,
                    ],
                    'faculty'                 => is_null($this->department) ? null : $this->department->faculty,
                    'isHeadOfDepartment'      => $this->is_head_of_department,
                    'isHeadOfFaculty'         => $this->is_head_of_faculty,
                    'permissions'             => $this->permissions,
                ];

            case Student::class:
                return [
                    'id'          => $this->id,
                    'name'        => $this->name,
                    'isFemale'    => $this->is_female,
                    'birth'       => $this->birth,
                    'address'     => $this->address,
                    'idClass'     => $this->id_class,
                    'idRole'      => $this->id_role,
                    'uuidAccount' => $this->uuid_account,
                    'permissions' => $this->permissions,
                ];

            default:
                return [];

        }

    }
}
