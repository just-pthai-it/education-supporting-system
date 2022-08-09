<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class NotificationCollection extends ResourceCollection
{
    private const MAXIMUM_NOTIFICATION_RECORDS_RETRIEVED = 6;

    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray ($request) : array
    {
        $hasNext = false;
        if ($this->collection->count() == self::MAXIMUM_NOTIFICATION_RECORDS_RETRIEVED)
        {
            $hasNext = true;
            $this->collection->pop();
        }

        return [
            'data'      => NotificationResource::collection($this->collection),
            'hasNext'   => $hasNext,
            'milestone' => $this->collection->last()->created_at,
        ];
    }
}
