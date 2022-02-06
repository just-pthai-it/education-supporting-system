<?php

namespace App\Repositories;

use App\Models\FixedSchedule;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Abstracts\BaseRepository;

class FixedScheduleRepository extends BaseRepository implements Contracts\FixedScheduleRepositoryContract
{
    public function model () : string
    {
        return FixedSchedule::class;
    }

    public function paginateByStatusAndIdDepartment ($id_department, $status)
    {
        $this->createModel();
        return $this->model->whereHas('schedule', function (Builder $query) use ($id_department)
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

    public function paginateByStatusAndIdTeacher ($id_teacher, $status)
    {
        $this->createModel();
        return $this->model->whereHas('schedule', function (Builder $query) use ($id_teacher)
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
