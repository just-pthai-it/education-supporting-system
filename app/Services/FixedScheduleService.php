<?php

namespace App\Services;

use App\Repositories\Contracts\FixedScheduleRepositoryContract;

class FixedScheduleService implements Contracts\FixedScheduleServiceContract
{
    private FixedScheduleRepositoryContract $fixedScheduleRepository;

    /**
     * @param FixedScheduleRepositoryContract $fixedScheduleRepository
     */
    public function __construct (FixedScheduleRepositoryContract $fixedScheduleRepository)
    {
        $this->fixedScheduleRepository = $fixedScheduleRepository;
    }

    public function createFixedSchedule ($fixed_schedule)
    {
        $fixed_schedule['status'] = 0;
        $this->fixedScheduleRepository->insert($fixed_schedule);
    }
}