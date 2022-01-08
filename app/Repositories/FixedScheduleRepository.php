<?php

namespace App\Repositories;

use App\Models\FixedSchedule;

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

    public function findByStatus ($status)
    {
        return FixedSchedule::with(['schedule:id,id_module_class', 'schedule.moduleClass:id,name,id_teacher',
                                    'schedule.moduleClass.teacher:id,name', 'newRoom:id', 'oldRoom:id'])
                            ->status($status)->orderBy('id', 'desc')->paginate(20);
    }

}
