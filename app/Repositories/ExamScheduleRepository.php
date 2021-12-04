<?php

namespace App\Repositories;

use App\Models\Teacher;
use App\Models\ModuleClass;
use App\Models\ExamSchedule;

class ExamScheduleRepository implements Contracts\ExamScheduleRepositoryContract
{
    public function insertMultiple ($exam_schedules)
    {
        ExamSchedule::insert($exam_schedules);
    }

    public function insertPivot ($id_module_class, $id_teachers)
    {
        ExamSchedule::where('id_module_class', $id_module_class)->first()->teachers()
                    ->attach($id_teachers);
    }

    public function findAllByIdTeacher ($id_teacher)
    {
        return Teacher::find($id_teacher)->examSchedules()
                      ->join(ModuleClass::table_as, 'mc.id', '=', 'exam_schedule.id_module_class')
                      ->get(['id_module_class', 'mc.name', 'method', 'time_start', 'time_end', 'id_room'])
                      ->toArray();
    }
}