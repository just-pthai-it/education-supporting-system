<?php

namespace App\Services\Contracts;

interface DepartmentServiceContract
{
    public function getTeachers (string $id_department, array $inputs);
}