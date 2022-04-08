<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'id'        => $this->id,
            'data'      => $this->data,
            'type'      => $this->type,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'readAt'    => $this->pivot->read_at,
            'sender'    => $this->account->accountable->name,
        ];
    }
}
