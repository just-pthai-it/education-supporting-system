<?php

namespace App\Services\Contracts;

interface ExamScheduleServiceContract
{
    public function getTeacherExamSchedules ($id_teacher);

    public function getDepartmentExamSchedules ($id_department);

    public function updateExamSchedule ($data);
}