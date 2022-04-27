<?php

namespace App\Services\Contracts;

interface ScheduleServiceContract
{
    public function readManyByIdDepartment (string $idDepartment, string $relation, array $inputs);

    public function readManyByIdTeacher (string $idTeacher, array $inputs);

    public function update (string $idSchedule, array $scheduleArr);
}
