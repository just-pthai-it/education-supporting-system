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
            'department'                => [
                'id'   => $this->department->id,
                'name' => $this->department->name,
            ],
            'faculty'                   => [
                'id'   => $this->department->faculty->id,
                'name' => $this->department->faculty->name,
            ],
        ];
    }
}
