<?php

namespace App\Http\Resources;

use JsonSerializable;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;

class FixedScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray ($request)
    {
        return [
            'id'          => $this->id,
            'teacher'     => $this->name,
            'oldSchedule' => [
                'date'  => $this->old_date,
                'shift' => $this->old_shift,
                'room'  => $this->old_id_room,
            ],
            'newSchedule' => [
                'date'  => $this->new_date,
                'shift' => $this->new_shift,
                'room'  => $this->new_id_room,
            ],
            'timeRequest' => $this->time_request,
            'timeAccept'  => $this->time_accept,
            'timeSetRoom' => $this->timme_set_room,
            'status'      => $this->status,
        ];
    }
}
