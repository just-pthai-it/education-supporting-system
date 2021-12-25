<?php

namespace App\Repositories;

use App\Models\Module;
use App\Models\ModuleClass;
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

    public function findAllByIdTeacher (string $id_teacher, array $id_study_sessions)
    {
        return Teacher::find($id_teacher)->moduleClasses()
                      ->join(Schedule::table_as, 'module_class.id', '=', 'sdu.id_module_class')
                      ->whereIn('module_class.id_study_session', $id_study_sessions)
                      ->orderBy('sdu.id_module_class')
                      ->orderBy('sdu.id')
                      ->get(['sdu.id as id_schedule', 'sdu.id_module_class',
                             'module_class.name as module_class_name',
                             'sdu.id_room', 'sdu.shift', 'sdu.date'])->toArray();
    }

    public function findAllByIdDepartment (string $id_department, array $id_study_sessions) : array
    {
        return Module::join(ModuleClass::table_as, 'mc.id_module', '=', 'module.id')
                     ->join(Schedule::table_as, 'mc.id', '=', 'sdu.id_module_class')
                     ->where('id_department', '=',  $id_department)
                     ->whereIn('mc.id_study_session', $id_study_sessions)
                     ->orderBy('sdu.id_module_class')
                     ->orderBy('sdu.id')
                     ->get(['sdu.id as id_schedule', 'sdu.id_module_class',
                            'mc.name as module_class_name',
                            'sdu.id_room', 'sdu.shift', 'sdu.date'])->toArray();
    }
}