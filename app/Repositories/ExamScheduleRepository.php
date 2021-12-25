<?php

namespace App\Repositories;

use App\Models\Teacher;
use App\Models\ModuleClass;
use App\Models\ExamSchedule;
use Illuminate\Support\Facades\DB;

class ExamScheduleRepository implements Contracts\ExamScheduleRepositoryContract
{
    public function upsertMultiple ($exam_schedules)
    {
        ExamSchedule::upsert($exam_schedules,
                             ['id_module_class'],
                             ['id_module_class' => DB::raw('id_module_class')]);
    }

    public function insertPivot ($id_module_class, $id_teachers)
    {
        ExamSchedule::where('id_module_class', $id_module_class)->first()
                    ->teachers()->sync($id_teachers);
    }

    public function findAllByIdTeacher ($id_teacher, $id_study_sessions)
    {
        return Teacher::find($id_teacher)->examSchedules()
                      ->join(ModuleClass::table_as, 'mc.id', '=', 'exam_schedule.id_module_class')
                      ->whereIn('mc.id_study_session', $id_study_sessions)
                      ->join('exam_schedule_teacher as est', 'est.id_exam_schedule', '=',
                             'exam_schedule.id')
                      ->join(Teacher::table_as, 'tea.id', '=', 'est.id_teacher')
                      ->get(['exam_schedule.id', 'id_module_class', 'mc.name', 'method',
                             'time_start', 'time_end', 'id_room', 'note',
                             'tea.name as teacher_name', 'est.position'])
                      ->toArray();
    }

    public function findAllByIdTeachers ($id_teachers, $id_study_sessions) : array
    {
        return Teacher::with([
                                 'examSchedules.moduleClass' => function ($query) use (
                                     $id_study_sessions
                                 )
                                 {
                                     return $query->whereIn('id_study_session', $id_study_sessions)
                                                  ->select('id', 'name');
                                 },])
                      ->select('id', 'name')->find($id_teachers)->toArray();
    }

    public function update ($new_exam_schedule)
    {
        $exam_schedule = ExamSchedule::find($new_exam_schedule['id']);
        foreach ($new_exam_schedule as $key => $value)
        {
            $exam_schedule->$key = strpos($key, 'is_') !== false ? intval($value) : $value;
        }
        $exam_schedule->save();
    }
}