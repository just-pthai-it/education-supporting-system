<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;

class FacultyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray ($request)
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'value' => $this->departments
        ];
    }
}
