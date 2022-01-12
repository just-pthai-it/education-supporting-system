<?php

namespace App\Services\Contracts;

interface TeacherServiceContract
{
    public function getTeachersByIdDepartment ($id_department);

    public function getFixedSchedulesByStatus ($id_teacher, $status);
}