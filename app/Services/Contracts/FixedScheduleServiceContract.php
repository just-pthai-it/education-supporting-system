<?php

namespace App\Services\Contracts;

interface FixedScheduleServiceContract
{
    public function create ($fixedScheduleArr);

    public function update ($fixedScheduleArr);

    public function readMany(array $inputs);
}