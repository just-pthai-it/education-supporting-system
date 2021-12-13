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
                      ->join('exam_schedule_teacher as est', 'est.id_exam_schedule', '=',
                             'exam_schedule.id')
                      ->join(Teacher::table_as, 'tea.id', '=', 'est.id_teacher')
                      ->get(['exam_schedule.id', 'id_module_class', 'mc.name', 'method',
                             'time_start', 'time_end', 'id_room', 'note',
                             'tea.name as teacher_name', 'est.position'])
                      ->toArray();
    }

    public function update ($data)
    {
        $exam_schedule = ExamSchedule::find($data['id']);
        foreach ($data as $key => $value)
        {
            $exam_schedule->$key = strpos($key, 'is_') !== false ? intval($value) : $value;
        }
        $exam_schedule->save();
    }
}