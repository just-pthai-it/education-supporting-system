<?php

namespace App\Services\Contracts;

interface TeacherServiceContract
{
    public function getTeachersByIdDepartment ($id_department);

    public function getExamSchedules ($id_teacher, $start, $end);

    public function getFixedSchedulesByStatus ($id_teacher, $status);
}