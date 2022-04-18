<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
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
        return [
            'id'                      => $this->id,
            'name'                    => $this->name,
            'isFemale'                => $this->is_female,
            'birth'                   => $this->birth,
            'email'                   => $this->account->email,
            'phone'                   => $this->account->phone,
            'universityTeacherDegree' => $this->university_teacher_degree,
            'department'              => [
                'id'   => $this->department->id,
                'name' => $this->department->name,
            ],
            'faculty'                 => [
                'id'   => $this->department->faculty->id,
                'name' => $this->department->faculty->name,
            ],
            'isHeadOfDepartment'      => $this->is_head_of_department,
            'isHeadOfFaculty'         => $this->is_head_of_faculty,
            'isActive'                => $this->is_active,
        ];
    }
}
