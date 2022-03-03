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

    public function findAllByIdTeacherAndDate ($id_teacher, $start, $end, $shift)
    {
        $this->createModel();
        return $this->model->whereHas('moduleClass', function (Builder $query) use ($id_teacher)
        {
            $query->where('id_teacher', $id_teacher);
        })->whereBetween('date', [$start, $end])->shift($shift)
                           ->with([
                                      'moduleClass'    => function ($query)
                                      {
                                          return $query->select('id', 'name',
                                                                DB::raw('\'self\' as teacher'));
                                      },
                                      'fixedSchedules' => function ($query)
                                      {
                                          return $query->whereIn('status', [0, 1, 2, 3, 4])
                                                       ->select('id', 'id_schedule', 'time_request',
                                                                'old_date', 'old_shift',
                                                                'old_id_room', 'new_date',
                                                                'new_shift', 'new_id_room',
                                                                'status');
                                      },
                                  ])->get();
    }

    public function findAllByIdDepartmentAndDate ($id_department, $start, $end)
    {
        $this->createModel();
        return $this->model->whereHas('moduleClass', function (Builder $query) use ($id_department)
        {
            $query->whereHas('module', function (Builder $query) use ($id_department)
            {
                $query->where('id_department', '=', $id_department);
            });
        })->whereBetween('date', [$start, $end])
                           ->with(['moduleClass:id,name,id_teacher',
                                   'moduleClass.teacher:id,name',
                                   'fixedSchedules' => function ($query)
                                   {
                                       return $query->whereIn('status', [0, 1, 2, 3, 4])
                                                    ->select('id', 'id_schedule', 'time_request',
                                                             'old_date', 'old_shift',
                                                             'old_id_room', 'new_date',
                                                             'new_shift', 'new_id_room',
                                                             'status');
                                   },
                                  ])->get();
    }

    public function findTeacherEmailByIdSchedule (int $id_schedule)
    {
        $this->createModel();
        return $this->model->find($id_schedule)->moduleClass()->first(['id_teacher'])->teacher()
                           ->first(['id'])->account()->first(['email'])->email;
    }
}
