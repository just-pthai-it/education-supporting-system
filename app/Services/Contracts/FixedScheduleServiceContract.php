<?php

namespace App\Services\Contracts;

interface FixedScheduleServiceContract
{
    public function readMany (array $inputs);

    public function readManyByIdDepartment (string $idDepartment, array $inputs);

    public function readManyByIdTeacher (string $idTeacher, array $inputs);

    public function create ($fixedScheduleArr);

    public function update ($fixedScheduleArr);

}