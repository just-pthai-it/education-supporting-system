<?php

namespace App\Http\Resources;

use JsonSerializable;
use App\Helpers\GData;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    public function __construct ($resource)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray ($request, $a = [])
    {
        if ($this->teacher != 'self')
        {
            $current_id_module = explode('-', $this->id_module_class)[0];
            if (GData::$current != $current_id_module)
            {
                GData::$current = $current_id_module;
                array_shift(GData::$colors);
            }
            $color = GData::$colors[0];
        }
        else
        {
            $current_id_module_class = $this->id_module_class;
            if (GData::$current != $current_id_module_class)
            {
                GData::$current = $current_id_module_class;
                array_shift(GData::$colors);
            }
            $color = GData::$colors[0];
        }

        return [
            'id'              => $this->id,
            'id_module_class' => $this->id_module_class,
            'name'            => $this->name,
            'id_room'         => $this->id_room,
            'shift'           => $this->shift,
            'date'            => $this->date,
            'id_module'       => explode('-', $this->id_module_class)[0],
            'teacher'         => $this->teacher,
            'color'           => $color,
        ];
    }
}
