<?php

namespace App\Repositories;

use App\Models\Teacher;
use App\Models\ModuleClass;
use App\Models\ExamSchedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

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

    public function findByIdTeacher ($id_teacher, $start, $end)
    {
        return ExamSchedule::whereHas('teachers', function (Builder $query) use ($id_teacher)
        {
            $query->where('teacher.id', '=', $id_teacher);
        })->whereBetween('time_start', [$start, $end])
                           ->with(['moduleClass:id,name',
                                   'teachers' => function ($query)
                                   {
                                       return $query->orderBy('position')->select('id', 'name');
                                   }])->get();
    }

    public function findByIdDepartment ($id_department, $start, $end)
    {
        return ExamSchedule::whereHas('moduleClass', function (Builder $query) use ($id_department)
        {
            $query->whereHas('module', function (Builder $query) use ($id_department)
            {
                $query->where('id_department', '=', $id_department);
            });
        })->whereBetween('time_start', [$start, $end])
                           ->with(['moduleClass:id,name',
                                   'teachers' => function ($query)
                                   {
                                       return $query->orderBy('position')->select('id', 'name');
                                   }])->get();
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