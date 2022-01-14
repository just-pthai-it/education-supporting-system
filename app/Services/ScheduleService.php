<?php

namespace App\Services;

use App\Repositories\Contracts\ScheduleRepositoryContract;

class ScheduleService implements Contracts\ScheduleServiceContract
{
    private ScheduleRepositoryContract $scheduleDepository;

    /**
     * @param ScheduleRepositoryContract $scheduleDepository
     */
    public function __construct (ScheduleRepositoryContract $scheduleDepository)
    {
        $this->scheduleDepository = $scheduleDepository;
    }

    public function updateSchedules ($object)
    {
        $this->scheduleDepository->update($object);
    }

    public function getTeacherSchedules ($id_teacher, $start, $end)
    {
        return $this->scheduleDepository->findAllByIdTeacher($id_teacher, $start, $end);
    }

    public function getDepartmentSchedules ($id_department, $start, $end)
    {
        return $this->scheduleDepository->findAllByIdDepartment($id_department, $start, $end);
    }
}
