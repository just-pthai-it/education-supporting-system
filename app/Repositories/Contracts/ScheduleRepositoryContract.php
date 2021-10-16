<?php

namespace App\Repositories\Contracts;

interface ScheduleRepositoryContract
{
    public function getSchedules1 ($id_student);

    public function getSchedules2 ($id_teacher);
}