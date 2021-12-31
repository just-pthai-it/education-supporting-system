<?php

namespace App\Repositories\Contracts;

interface FixedScheduleRepositoryContract
{
    public function insert ($fixed_schedule);

    public function findByStatus ($status);
}
