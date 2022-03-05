<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ModuleClassResource extends JsonResource
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
            'id'            => $this->id,
            'name'          => $this->name,
            'credit'        => $this->credit,
            'numberReality' => $this->number_reality,
            'classType'     => $this->class_type,
            'teacher'       => $this->teacher,
        ];
    }
}
