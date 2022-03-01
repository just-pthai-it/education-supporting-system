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

    public function findByIdDepartment ($id_department, array $conditions)
    {
        $this->createModel();
        $this->model = $this->model->whereHas('schedule',
            function (Builder $query) use ($id_department)
            {
                $query->whereHas('moduleClass', function (Builder $query) use ($id_department)
                {
                    $query->whereHas('module', function (Builder $query) use ($id_department)
                    {
                        return $query->where('id_department', '=', $id_department);
                    })->where('id_teacher', '=', '0884');
                });
            })->status($conditions['status']);

        if (isset($conditions['old_date']) &&
            isset($conditions['new_date']))
        {
            $this->model = $this->model->where(function ($query) use ($conditions)
            {
                $query->whereBetween('old_date',
                                     explode(',', $conditions['old_date']))
                      ->orWhereBetween('new_date',
                                       explode(',', $conditions['new_date']));
            });
        }

        $this->model = $this->model->orderBy('id', 'desc')
                                   ->with(['schedule:id,id_module_class', 'schedule.moduleClass:id,name,id_teacher',
                                           'schedule.moduleClass.teacher:id,name']);

        if (isset($conditions['page']))
        {
            return $this->model->paginate($conditions['pagination'] ?? 20);
        }

        return $this->model->get();
    }

    public function findByIdTeacher ($id_teacher, $status)
    {
        $this->createModel();
        return $this->model->whereHas('schedule', function (Builder $query) use ($id_teacher)
        {
            $query->whereHas('moduleClass', function (Builder $query) use ($id_teacher)
            {
                $query->where('id_teacher', '=', $id_teacher);
            });
        })->status($status)->orderBy('id', 'desc')
                           ->with(['schedule:id,id_module_class', 'schedule.moduleClass:id,name'])
                           ->paginate(20);
    }
}
