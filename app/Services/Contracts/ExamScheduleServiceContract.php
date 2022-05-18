<?php

namespace App\Services\Contracts;

interface ExamScheduleServiceContract
{
    public function readManyByIdDepartment (string $idDepartment, array $inputs);

    public function readManyByIdTeacher (string $idTeacher, array $inputs);

    public function update ($examSchedule);

    public function updateV1 (string $idExamSchedule, array $inputs);

    public function createExamScheduleTeacher (string $idExamSchedule, array $inputs);
}