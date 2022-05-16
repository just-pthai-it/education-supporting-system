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

    public function findByIdTeacher ($id_teacher, array $inputs)
    {
        $this->createModel();
        return $this->model->whereHas('teachers', function (Builder $query) use ($id_teacher)
        {
            $query->where('teachers.id', '=', $id_teacher);
        })->filter($inputs)->with(['moduleClass:id,name',
                                   'teachers' => function ($query)
                                   {
                                       return $query->select('id_teacher', 'name')
                                                    ->orderBy('pivot_id');
                                   },])->get();
    }

    public function findByIdDepartment ($id_department, array $inputs)
    {
        $this->createModel();
        return $this->model->whereHas('moduleClass', function (Builder $query) use ($id_department)
        {
            $query->whereHas('module', function (Builder $query) use ($id_department)
            {
                $query->where('id_department', '=', $id_department);
            });
        })->filter($inputs)->with(['moduleClass:id,name',
                                   'teachers' => function ($query)
                                   {
                                       return $query->select('id_teacher', 'name')
                                                    ->orderBy('pivot_id');
                                   },])->get();
    }
}