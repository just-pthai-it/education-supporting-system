<?php

namespace App\Repositories\Contracts;

interface FixedScheduleRepositoryContract
{
    public function insert ($fixed_schedule);

    public function update ($object, $column, $operator = '=');

    public function findByStatus ($status);
}
