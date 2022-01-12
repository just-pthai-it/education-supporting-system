<?php

namespace App\Services\Contracts;

interface DepartmentServiceContract
{
    public function getAllDepartments ();

    public function getFixedSchedulesByStatus ($id_department, $status);
}