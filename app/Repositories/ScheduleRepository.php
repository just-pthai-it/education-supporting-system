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

    public function update ($object, $column = 'id', $operator = '=')
    {
        Schedule::where($column, $operator, array_shift($object))->update($object);
    }

    public function findById ($id, $columns = ['*'])
    {
        return Schedule::find($id, $columns);
    }

    public function findAllByIdTeacher ($id_teacher, $start, $end)
    {
        return Teacher::find($id_teacher)->moduleClasses()
                      ->join(Schedule::table_as, 'module_class.id', '=', 'sdu.id_module_class')
                      ->whereBetween('date', [$start, $end])
            //                      ->orderBy('sdu.id_module_class')
            //                      ->orderBy('sdu.id')
                      ->get(['sdu.id', 'sdu.id_module_class', 'module_class.name',
                             'sdu.id_room', 'sdu.shift', 'sdu.date', DB::raw('\'self\' as teacher'),]);
    }

    public function findAllByIdDepartment ($id_department, $start, $end)
    {
        return ModuleClass::join(Module::table_as, 'module_class.id_module', '=', 'md.id')
                          ->join(Schedule::table_as, 'module_class.id', '=', 'sdu.id_module_class')
                          ->leftJoin(Teacher::table_as, 'tea.id', '=', 'module_class.id_teacher')
                          ->where('md.id_department', '=', $id_department)
                          ->whereBetween('date', [$start, $end])
            //                          ->orderBy('sdu.id_module_class')
            //                          ->orderBy('sdu.id')
                          ->get(['sdu.id', 'sdu.id_module_class', 'module_class.name',
                                 'sdu.id_room', 'sdu.shift', 'sdu.date', 'tea.name as teacher']);
    }
}
