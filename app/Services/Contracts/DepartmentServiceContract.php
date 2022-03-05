<?php

namespace App\Services\Contracts;

interface DepartmentServiceContract
{
    public function getSchedules ($id_department, array $inputs);

    public function getExamSchedules ($id_department, array $inputs);

    public function getFixedSchedules ($id_department, array $inputs);

    public function getModuleClasses ($id_department, array $inputs);

    public function getTeachers ($id_department);
}