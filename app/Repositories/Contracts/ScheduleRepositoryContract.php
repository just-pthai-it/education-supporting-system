<?php

namespace App\Repositories\Contracts;

interface ScheduleRepositoryContract
{
    public function getSchedules ($id_teacher);
}