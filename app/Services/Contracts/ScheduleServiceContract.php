<?php

namespace App\Services\Contracts;

interface ScheduleServiceContract
{
    public function update (string $idSchedule, array $scheduleArr);
}
