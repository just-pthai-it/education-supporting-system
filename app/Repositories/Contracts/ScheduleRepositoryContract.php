<?php

namespace App\Repositories\Contracts;

interface ScheduleRepositoryContract extends BaseRepositoryContract
{
    public function findAllByIdTeachers ($idTeachers, array $inputs);

    public function findAllByIdDepartment (string $idDepartment, array $inputs);
}