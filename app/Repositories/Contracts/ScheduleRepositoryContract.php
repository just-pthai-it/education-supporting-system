<?php

namespace App\Repositories\Contracts;

interface ScheduleRepositoryContract extends BaseRepositoryContract
{
    public function findAllByIdTeacher (string $idTeacher, array $inputs);

    public function findAllByIdDepartment (string $idDepartment, array $inputs);
}