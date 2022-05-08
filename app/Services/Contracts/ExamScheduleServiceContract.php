<?php

namespace App\Services\Contracts;

interface ExamScheduleServiceContract
{
    public function readManyByIdDepartment (string $idDepartment, array $inputs);

    public function readManyByIdTeacher (string $idTeacher, array $inputs);

    public function update ($examSchedule);

    public function updateProctors (string $idExamSchedule, array $inputs);
}