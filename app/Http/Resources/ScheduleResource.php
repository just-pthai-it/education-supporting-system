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
        $arr         = explode('-', $this->id_module_class);
        $id_module   = $arr[0];
        $module_name = str_replace('-' . $arr[1] . '-' . $arr[2] . '-' . $arr[3],
                                   '', $this->name);

        if ($this->teacher != 'self')
        {
            $current_id_module = $id_module;
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
            'id_module'       => $id_module,
            'mmodule_name'    => $module_name,
            'teacher'         => $this->teacher,
            'color'           => $color,
        ];
    }
}
