<?php

namespace App\Repositories\Contracts;

interface ScheduleRepositoryContract
{
    public function getStudentSchedules ($id_student);

    public function getTeacherSchedules ($id_teacher);
}