<?php

namespace App\Http\Resources;

use JsonSerializable;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamScheduleResource extends JsonResource
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
            'id'               => $this->id,
            'id_module_class'  => $this->id_module_class,
            'name'             => $this->moduleClass->name ?? null,
            'method'           => $this->method,
            'startAt'          => $this->start_at,
            'endAt'            => $this->end_at,
            'numberOfStudents' => $this->number_of_students,
            'idRoom'           => $this->id_room,
            'note'             => $this->note,
            'teachers'         => $this->teachers->pluck('name'),
        ];
    }
}
