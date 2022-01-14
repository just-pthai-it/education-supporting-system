<?php

namespace App\Services\Contracts;

interface ScheduleServiceContract
{
    public function updateSchedules ($object);

    public function getTeacherSchedules ($id_teacher, $start, $end);

    public function getDepartmentSchedules ($id_department, $start, $end);
}
