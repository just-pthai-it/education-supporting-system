<?php

namespace App\Repositories;

use App\Models\Module;
use App\Models\ModuleClass;
use App\Models\Schedule;
use App\Models\Teacher;
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
                      ->get(['sdu.id', 'sdu.id_module_class', 'module_class.name',
                             'sdu.id_room', 'sdu.shift', 'sdu.date', DB::raw('\'self\' as teacher'), ]);
    }

    public function findAllByIdDepartment (string $id_department, array $id_study_sessions)
    {
        return ModuleClass::join(Module::table_as, 'module_class.id_module', '=', 'md.id')
                          ->join(Schedule::table_as, 'module_class.id', '=', 'sdu.id_module_class')
                          ->leftJoin(Teacher::table_as, 'tea.id', '=', 'module_class.id_teacher')
                          ->where('md.id_department', '=', $id_department)
                          ->whereIn('module_class.id_study_session', $id_study_sessions)
                          ->orderBy('sdu.id_module_class')
                          ->orderBy('sdu.id')
                          ->get(['sdu.id', 'sdu.id_module_class', 'module_class.name',
                                 'sdu.id_room', 'sdu.shift', 'sdu.date', 'tea.name as teacher']);
    }
}
