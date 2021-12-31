<?php

namespace App\Repositories;

use App\Models\FixedSchedule;

class FixedScheduleRepository implements Contracts\FixedScheduleRepositoryContract
{
    public function insert ($fixed_schedule)
    {
        FixedSchedule::create($fixed_schedule);
    }
}
