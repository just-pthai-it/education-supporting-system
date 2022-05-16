<?php

namespace App\Repositories\Contracts;

interface ExamScheduleRepositoryContract extends BaseRepositoryContract
{
    public function findByIdTeacher ($idTeacher, array $inputs);

    public function findByIdDepartment (string $idDepartment, array $inputs);
}