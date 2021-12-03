<?php

namespace App\Services\Contracts;

interface ScheduleServiceContract
{
    public function getTeacherSchedules ($id_teacher);

    public function getDepartmentSchedules ($id_department);
}
