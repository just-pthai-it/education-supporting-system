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
            'id'                        => $this->id,
            'name'                      => $this->name,
            'is_female'                 => $this->is_female,
            'birth'                     => $this->birth,
            'university_teacher_degree' => $this->university_teacher_degree,
            'department'                => $this->department->name,
            'faculty'                   => $this->department->faculty->name,
        ];
    }
}
