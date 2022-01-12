<?php

namespace App\Repositories;

use App\Models\FixedSchedule;
use Illuminate\Database\Eloquent\Builder;

class FixedScheduleRepository implements Contracts\FixedScheduleRepositoryContract
{
    public function insert ($fixed_schedule)
    {
        FixedSchedule::create($fixed_schedule);
    }

    public function update ($object, $column = 'id', $operator = '=')
    {
        FixedSchedule::where($column, $operator, array_shift($object))->update($object);
    }

    public function findByStatusAndIdDepartment ($id_department, $status)
    {
        return FixedSchedule::whereHas('schedule', function (Builder $query) use ($id_department)
        {
            $query->whereHas('moduleClass', function (Builder $query) use ($id_department)
            {
                $query->whereHas('module', function (Builder $query) use ($id_department)
                {
                    return $query->where('id_department', '=', $id_department);
                })->where('id_teacher', '=', '0884');
            });
        })->status($status)->orderBy('id', 'desc')
                            ->with(['schedule:id,id_module_class', 'schedule.moduleClass:id,name,id_teacher',
                                    'schedule.moduleClass.teacher:id,name', 'newRoom:id', 'oldRoom:id'])
                            ->paginate(20);
    }

    public function findByStatusAndIdTeacher ($id_teacher, $status)
    {
        return FixedSchedule::whereHas('schedule', function (Builder $query) use ($id_teacher)
        {
            $query->whereHas('moduleClass', function (Builder $query) use ($id_teacher)
            {
                $query->where('id_teacher', '=', $id_teacher);
            });
        })->status($status)->orderBy('id', 'desc')
                            ->with(['schedule:id,id_module_class', 'schedule.moduleClass:id,name,id_teacher',
                                    'schedule.moduleClass.teacher:id,name', 'newRoom:id', 'oldRoom:id'])
                            ->paginate(20);
    }
}
