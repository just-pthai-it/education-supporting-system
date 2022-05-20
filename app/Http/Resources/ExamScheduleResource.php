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
        if (!is_null($request->route('id_teacher')))
        {
            $note = $this->teachers->where('id_teacher', '=', $request->route('id_teacher'))
                                   ->first()->note;
        }
        else
        {
            $note = null;
        }

        return [
            'id'               => $this->id,
            'idModuleClass'    => $this->id_module_class,
            'name'             => $this->moduleClass->name ?? null,
            'credit'           => $this->moduleClass->module->credit,
            'method'           => $this->method,
            'startAt'          => $this->start_at,
            'endAt'            => $this->end_at,
            'numberOfStudents' => $this->number_of_students,
            'idRoom'           => $this->id_room,
            'note'             => $note,
            'teachers'         => $this->teachers->pluck('name'),
        ];
    }
}
