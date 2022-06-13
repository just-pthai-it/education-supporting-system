<?php

namespace App\Repositories\Contracts;

interface ScheduleRepositoryContract extends BaseRepositoryContract
{
    public function findAllByIdTeachers (array $idTeachers, array $inputs);

    public function findAllByIdDepartment (string $idDepartment, array $inputs);

    public function findAllByIdStudent (string $idStudent, array $inputs);
}