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

    public function updateSchedules ($schedule)
    {
        $id = array_shift($schedule);
        $this->scheduleDepository->updateByIds($id, $schedule);
    }
}
