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

    public function getTeacherSchedules ($id_teacher) : array
    {
        return $this->scheduleDepository->getSchedules($id_teacher);
    }
}
