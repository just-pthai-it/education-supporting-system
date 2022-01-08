<?php

namespace App\Repositories\Contracts;

interface FixedScheduleRepositoryContract
{
    public function insert ($fixed_schedule);

    public function update ($object, $column = 'id', $operator = '=');

    public function findByStatus ($status);
}
