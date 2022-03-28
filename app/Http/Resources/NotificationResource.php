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
            'createdAt' => date_format($this->created_at, "Y-m-d H:i:s"),
            'updatedAt' => date_format($this->updated_at, "Y-m-d H:i:s"),
            'readAt'    => $this->pivot->read_at,
            'sender'    => $this->account->accountable->name,
        ];
    }
}
