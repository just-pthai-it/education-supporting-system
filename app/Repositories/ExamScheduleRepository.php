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

    public function findByIdTeacher ($idTeacher, array $inputs)
    {
        $this->createModel();
        $this->model = $this->model->whereHas('teachers', function (Builder $query) use ($idTeacher)
        {
            $query->where('teachers.id', '=', $idTeacher);
        });

        if (isset($inputs['id_study_session']))
        {
            $this->model = $this->model->whereHas('moduleClass',
                function (Builder $query) use ($inputs)
                {
                    $query->where('id_study_session', '=', $inputs['id_study_session']);
                });
        }

        return $this->model->filter($inputs)->with(['moduleClass:id,name,id_module',
                                                    'moduleClass.module:id,credit',
                                                    'teachers' => function ($query)
                                                    {
                                                        return $query->select('id_teacher', 'name',
                                                                              'note')
                                                                     ->orderBy('pivot_id');
                                                    },])->get();
    }

    public function findByIdStudent (string $idStudent, array $inputs)
    {
        $this->createModel();
        return $this->model->whereHas('moduleClass', function (Builder $query) use ($idStudent)
        {
            if (isset($inputs['id_study_session']))
            {
                $query->where('id_study_session', '=', $inputs['id_study_session']);
            }

            $query->whereHas('students', function (Builder $query) use ($idStudent)
            {
                $query->where('id_student', '=', $idStudent);
            });
        })->filter($inputs)->with(['moduleClass:id,name,id_module',
                                   'moduleClass.module:id,credit',
                                   'teachers' => function ($query)
                                   {
                                       return $query->select('id_teacher', 'name',
                                                             'note')
                                                    ->orderBy('pivot_id');
                                   },])->get();
    }

    public function findByIdDepartment (string $idDepartment, array $inputs)
    {
        $this->createModel();
        return $this->model->whereHas('moduleClass',
            function (Builder $query) use ($idDepartment, $inputs)
            {
                if (isset($inputs['id_study_session']))
                {
                    $query->where('id_study_session', '=', $inputs['id_study_session']);
                }

                $query->whereHas('module', function (Builder $query) use ($idDepartment)
                {
                    $query->where('id_department', '=', $idDepartment);
                });
            })->filter($inputs)->with(['moduleClass:id,name,id_module',
                                       'moduleClass.module:id,credit',
                                       'teachers' => function ($query)
                                       {
                                           return $query->select('id_teacher', 'name', 'note')
                                                        ->orderBy('pivot_id');
                                       },])->get();
    }
}