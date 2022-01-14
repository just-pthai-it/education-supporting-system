<?php

namespace App\Repositories;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\Builder;

class ScheduleRepository implements Contracts\ScheduleRepositoryContract
{
    public function insertMultiple ($data)
    {
        Schedule::insert($data);
    }

    public function update ($object, $column = 'id', $operator = '=')
    {
        Schedule::where($column, $operator, array_shift($object))->update($object);
    }

    public function findById ($id, $columns = ['*'])
    {
        return Schedule::find($id, $columns);
    }

    public function findAllByIdTeacher ($id_teacher, $start, $end)
    {
        return Schedule::whereHas('moduleClass', function (Builder $query)
        {
            $query->where('id_teacher', '0884');
        })->whereBetween('date', [$start, $end])
                       ->with(['moduleClass:id,name',
                               'fixedSchedules' => function ($query)
                               {
                                   return $query->where('status', '=', 1)
                                                ->select('id_schedule', 'new_date',
                                                         'new_shift', 'new_id_room');
                               },])->get();
    }

    public function findAllByIdDepartment ($id_department, $start, $end)
    {
        return Schedule::whereHas('moduleClass', function (Builder $query) use ($id_department)
        {
            $query->whereHas('module', function (Builder $query) use ($id_department)
            {
                $query->where('id_department', '=', $id_department);
            });
        })->whereBetween('date', [$start, $end])
                       ->with(['moduleClass:id,name,id_teacher',
                               'moduleClass.teacher:id,name'])->get();
    }
}
