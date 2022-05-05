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

    public function findByIdDepartment ($id_department, array $inputs)
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
                    });
                });
            })->filter($inputs)->with(['schedule:id,id_module_class',
                                       'schedule.moduleClass:id,name,id_teacher,number_reality',
                                       'schedule.moduleClass.teacher:id,name']);

        if (isset($inputs['page']))
        {
            return $this->model->paginate($inputs['pagination'] ?? 20);
        }

        return $this->model->get();
    }

    public function findByIdTeacher ($id_teacher, array $inputs)
    {
        $this->createModel();
        $this->model = $this->model->whereHas('schedule',
            function (Builder $query) use ($id_teacher)
            {
                $query->whereHas('moduleClass', function (Builder $query) use ($id_teacher)
                {
                    $query->where('id_teacher', '=', $id_teacher);
                });
            })->filter($inputs)->with(['schedule:id,id_module_class',
                                       'schedule.moduleClass:id,name,number_reality']);

        if (isset($inputs['page']))
        {
            return $this->model->paginate($inputs['pagination'] ?? 20);
        }

        return $this->model->get();
    }
}
