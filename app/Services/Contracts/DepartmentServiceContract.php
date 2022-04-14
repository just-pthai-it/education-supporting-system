<?php

namespace App\Services\Contracts;

interface DepartmentServiceContract
{
    public function getExamSchedules ($id_department, array $inputs);

    public function getTeachers (string $id_department, array $inputs);
}