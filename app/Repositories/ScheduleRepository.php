<?php

namespace App\Repositories;

use App\Helpers\GData;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Abstracts\BaseRepository;

class ScheduleRepository extends BaseRepository implements Contracts\ScheduleRepositoryContract
{
    public function model () : string
    {
        return Schedule::class;
    }

    public function findAllByIdTeachers (array $idTeachers, array $inputs)
    {
        $this->createModel();
        return $this->model->whereHas('moduleClass',
            function (Builder $query) use ($idTeachers, $inputs)
            {
                $query->whereIn('id_teacher', $idTeachers);
                if (isset($inputs['id_study_session']))
                {
                    $query->where('id_study_session', '=',
                                  $inputs['id_study_session']);
                }
            })->filter($inputs)->with([
                                          'moduleClass'    => function ($query) use (
                                              $idTeachers, $inputs
                                          )
                                          {
                                              if (count($idTeachers) > 1)
                                              {
                                                  $query->select('id', 'name', 'id_teacher')
                                                        ->with(['teacher' => function ($query)
                                                        {
                                                            $query->select('id', 'name')
                                                                  ->where('is_active', '=', 1);
                                                        }]);
                                              }
                                              else
                                              {
                                                  $query->select('id', 'name');
                                              }
                                          },
                                          'fixedSchedules' => function ($query)
                                          {
                                              $query->whereIn('status',
                                                              array_merge(array_values(GData::$fsStatusCode['pending']),
                                                                          array_values(GData::$fsStatusCode['approve'])))
                                                    ->select('id', 'id_schedule', 'created_at',
                                                             'old_date', 'old_shift',
                                                             'old_id_room', 'new_date',
                                                             'new_shift', 'new_id_room',
                                                             'intend_time', 'status');
                                          },
                                      ])->get();
    }

    public function findAllByIdDepartment (string $idDepartment, array $inputs)
    {
        $this->createModel();
        return $this->model->whereHas('moduleClass',
            function (Builder $query) use ($idDepartment, $inputs)
            {
                if (isset($inputs['id_study_session']))
                {
                    $query->where('id_study_session', '=',
                                  $inputs['id_study_session']);
                }

                $query->whereHas('module', function (Builder $query) use ($idDepartment)
                {
                    $query->where('id_department', '=', $idDepartment);
                });
            })->filter($inputs)->with(['moduleClass:id,name,id_teacher',
                                       'moduleClass.teacher:id,name',
                                       'fixedSchedules' => function ($query)
                                       {
                                           return $query->whereIn('status',
                                                                  array_merge(array_values(GData::$fsStatusCode['pending']),
                                                                              array_values(GData::$fsStatusCode['approve'])))
                                                        ->select('id', 'id_schedule', 'created_at',
                                                                 'old_date', 'old_shift',
                                                                 'old_id_room', 'new_date',
                                                                 'new_shift', 'new_id_room',
                                                                 'intend_time', 'status');
                                       },
                                      ])->get();
    }

    public function findAllByIdStudent (string $idStudent, array $inputs)
    {
        $this->createModel();
        return $this->model->whereHas('moduleClass', function (Builder $query) use ($idStudent)
        {
            $query->whereHas('students', function (Builder $query) use ($idStudent)
            {
                $query->where('id_student', '=', $idStudent);
            });
        })->filter($inputs)->with(['moduleClass:id,name,id_teacher',
                                   'moduleClass.teacher:id,name'])->get();
    }
}
