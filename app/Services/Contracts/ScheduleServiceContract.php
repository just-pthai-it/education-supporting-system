<?php

namespace App\Services\Contracts;

interface ScheduleServiceContract
{
    public function getStudentSchedules ($id_student);

    public function getTeacherSchedules ($id_teacher);
}
