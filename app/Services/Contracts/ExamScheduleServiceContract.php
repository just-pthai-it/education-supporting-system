<?php

namespace App\Services\Contracts;

interface ExamScheduleServiceContract
{
    public function getTeacherExamSchedules ($id_teacher);
}