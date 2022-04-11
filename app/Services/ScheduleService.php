<?php

namespace App\Services;

use Illuminate\Support\Arr;
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

    public function update ($schedule)
    {
        $this->scheduleDepository->updateByIds($schedule['id'], Arr::except($schedule, ['id']));
    }
}
