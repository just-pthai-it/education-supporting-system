<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FixedScheduleResource extends JsonResource
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
            'id'              => $this->id,
            'teacher'         => $this->schedule->moduleClass->teacher->name,
            'moduleClassName' => $this->schedule->moduleClass->name,
            'oldSchedule'     => [
                'date'  => $this->old_date,
                'shift' => $this->old_shift,
                'room'  => $this->old_id_room,
            ],
            'newSchedule'     => [
                'date'  => $this->new_date,
                'shift' => $this->new_shift,
                'room'  => $this->new_id_room,
            ],
            'timeRequest'     => $this->time_request,
            'timeAccept'      => $this->time_accept,
            'timeSetRoom'     => $this->timme_set_room,
            'status'          => $this->status,
            'reason'          => $this->reason,
        ];
    }
}
