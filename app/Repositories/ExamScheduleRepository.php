<?php

namespace App\Repositories;

use App\Models\ExamSchedule;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Abstracts\BaseRepository;

class ExamScheduleRepository extends BaseRepository implements Contracts\ExamScheduleRepositoryContract
{
    public function model () : string
    {
        return ExamSchedule::class;
    }

    public function findByIdTeacher ($id_teacher, $start, $end)
    {
        $this->createModel();
        return $this->model->whereHas('teachers', function (Builder $query) use ($id_teacher)
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
        $this->createModel();
        return $this->model->whereHas('moduleClass', function (Builder $query) use ($id_department)
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
}