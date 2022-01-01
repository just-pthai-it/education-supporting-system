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

    public function updateFixedSchedule ($object)
    {
        $this->fixedScheduleRepository->update($object, 'id');
    }

    public function getFixedSchedulesByStatus ($status)
    {
        return $this->fixedScheduleRepository->findByStatus($status);
    }
}