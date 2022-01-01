<?php

namespace App\Services\Contracts;

interface FixedScheduleServiceContract
{
    public function createFixedSchedule ($fixed_schedule);

    public function updateFixedSchedule ($object);

    public function getFixedSchedulesByStatus ($status);
}