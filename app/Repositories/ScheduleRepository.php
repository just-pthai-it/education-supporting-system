<?php

namespace App\Repositories;

use App\Models\Schedule;
use App\Models\Teacher;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ScheduleRepository implements Contracts\ScheduleRepositoryContract
{
    public function insertMultiple ($data)
    {
        Schedule::insert($data);
    }

    public function getSchedules ($id_teacher) : Collection
    {
        return Teacher::find($id_teacher)->moduleClasses()
                      ->join(Schedule::table_as, 'module_class.id', '=', 'sdu.id_module_class')
                      ->where('sdu.date', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 1 YEAR)'))
                      ->orderBy('sdu.id_module_class')
                      ->orderBy('sdu.id')
                      ->select('sdu.id as id_schedule', 'sdu.id_module_class',
                               'module_class.module_class_name',
                               'sdu.id_room', 'sdu.shift', 'sdu.date')->get();
    }
}