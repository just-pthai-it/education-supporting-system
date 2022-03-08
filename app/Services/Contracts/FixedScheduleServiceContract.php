<?php

namespace App\Services\Contracts;

interface FixedScheduleServiceContract
{
    public function createFixedSchedule ($fixedScheduleArr);

    public function updateFixedSchedule ($fixedScheduleArr);

    public function paginateFixedSchedulesByStatus(string $status, string $pagination);
}