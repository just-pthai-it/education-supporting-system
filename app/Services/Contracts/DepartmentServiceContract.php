<?php

namespace App\Services\Contracts;

interface DepartmentServiceContract
{
    public function getAllDepartments ();

    public function getExamSchedules ($id_department, $start, $end);

    public function getFixedSchedulesByStatus ($id_department, $status);
}