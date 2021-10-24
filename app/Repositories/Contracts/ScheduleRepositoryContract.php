<?php

namespace App\Repositories\Contracts;

interface ScheduleRepositoryContract
{
    public function insertMultiple ($data);

    public function getSchedules ($id_teacher);
}