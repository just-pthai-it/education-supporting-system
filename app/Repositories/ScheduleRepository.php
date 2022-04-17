<?php

namespace App\Repositories;

use App\Models\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Abstracts\BaseRepository;

class ScheduleRepository extends BaseRepository implements Contracts\ScheduleRepositoryContract
{
    public function model () : string
    {
        return Schedule::class;
    }

    public function findAllByIdTeacher (string $idTeacher, array $inputs)
    {
        $this->createModel();
        return $this->model->whereHas('moduleClass', function (Builder $query) use ($idTeacher)
        {
            $query->where('id_teacher', $idTeacher);
        })->filter($inputs)->with([
                                      'moduleClass'    => function ($query)
                                      {
                                          return $query->select('id', 'name',
                                                                DB::raw('\'self\' as teacher'));
                                      },
                                      'fixedSchedules' => function ($query)
                                      {
                                          return $query->whereIn('status', [0, 1, 2, 3, 4, 5, 6])
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
        return $this->model->whereHas('moduleClass', function (Builder $query) use ($idDepartment)
        {
            $query->whereHas('module', function (Builder $query) use ($idDepartment)
            {
                $query->where('id_department', '=', $idDepartment);
            });
        })->filter($inputs)->with(['moduleClass:id,name,id_teacher',
                                   'moduleClass.teacher:id,name',
                                   'fixedSchedules' => function ($query)
                                   {
                                       return $query->whereIn('status', [0, 1, 2, 3, 4, 5, 6])
                                                    ->select('id', 'id_schedule', 'created_at',
                                                             'old_date', 'old_shift',
                                                             'old_id_room', 'new_date',
                                                             'new_shift', 'new_id_room',
                                                             'intend_time', 'status');
                                   },
                                  ])->get();
    }
}
