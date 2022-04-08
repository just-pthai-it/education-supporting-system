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
        $teachers = [];
        foreach ($this->teachers as $teacher)
        {
            $teachers[] = $teacher->name;
        }

        return [
            'idModuleClass' => $this->id_module_class,
            'name'          => $this->m,
            'method'        => $this->method,
            'startAt'       => $this->start_at,
            'endAt'         => $this->end_at,
            'idRoom'        => $this->id_room,
            'note'          => $this->method,
            'teachers'      => $teachers,
        ];
    }
}
