<?php

namespace App\Services\Contracts;

interface ScheduleServiceContract
{
    public function getTeacherSchedules ($id_teacher, $term, $study_sessions);

    public function getDepartmentSchedules ($id_department, $term, $study_sessions);
}
