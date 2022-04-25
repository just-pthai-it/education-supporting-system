<?php

namespace App\Services\Contracts;

interface ScheduleServiceContract
{
    public function readManyByIdDepartment (string $idDepartment, array $inputs);

    public function readManyByIdTeacher (string $idTeacher, array $inputs);

    public function readManyByTeachersInDepartment (string $idDepartment);

    public function update (string $idSchedule, array $scheduleArr);
}
