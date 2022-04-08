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
            'id'                       => $this->id,
            'idSchedule'               => $this->id_schedule,
            'teacher'                  => [
                'id'   => $this->schedule->moduleClass->teacher->id ?? null,
                'name' => $this->schedule->moduleClass->teacher->name ?? null,
            ],
            'moduleClassName'          => $this->schedule->moduleClass->name,
            'moduleClassNumberReality' => $this->schedule->moduleClass->number_reality,
            'oldSchedule'              => [
                'date'  => $this->old_date,
                'shift' => $this->old_shift,
                'room'  => $this->old_id_room,
            ],
            'newSchedule'              => [
                'date'  => $this->new_date,
                'shift' => $this->new_shift,
                'room'  => $this->new_id_room,
            ],
            'createdAt'                => $this->created_at,
            'acceptedAt'               => $this->accepted_at,
            'setRoomAt'                => $this->set_room_at,
            'status'                   => $this->status,
            'reason'                   => $this->reason,
        ];
    }
}
