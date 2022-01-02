<?php

namespace App\Services;

use App\Repositories\Contracts\ScheduleRepositoryContract;
use App\Repositories\Contracts\FixedScheduleRepositoryContract;

class FixedScheduleService implements Contracts\FixedScheduleServiceContract
{
    private FixedScheduleRepositoryContract $fixedScheduleRepository;
    private ScheduleRepositoryContract $scheduleRepository;

    /**
     * @param FixedScheduleRepositoryContract $fixedScheduleRepository
     * @param ScheduleRepositoryContract      $scheduleRepository
     */
    public function __construct (FixedScheduleRepositoryContract $fixedScheduleRepository,
                                 ScheduleRepositoryContract      $scheduleRepository)
    {
        $this->fixedScheduleRepository = $fixedScheduleRepository;
        $this->scheduleRepository      = $scheduleRepository;
    }

    public function createFixedSchedule ($fixed_schedule)
    {
        $this->_fillData($fixed_schedule);
        $this->fixedScheduleRepository->insert($fixed_schedule);
    }

    private function _fillData (&$fixed_schedule)
    {
        $schedule = $this->_getScheduleById($fixed_schedule['id_schedule']);
        $fixed_schedule['old_date']    = $schedule->date;
        $fixed_schedule['old_shift']   = $schedule->shift;
        $fixed_schedule['old_id_room'] = $schedule->id_room;
    }

    private function _getScheduleById ($id)
    {
        return $this->scheduleRepository->findById($id, ['date', 'shift', 'id_room']);
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