<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FacultyResource extends JsonResource
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
        $response = [
            'id'   => $this->id,
            'name' => $this->name,
        ];

        if (request()->route('additional') == 'with-departments')
        {
            $response['value'] = DepartmentResource::collection($this->departments)->all();
        }

        return $response;
    }
}
