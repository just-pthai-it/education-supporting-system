<?php

namespace App\Services\Contracts;

interface ExamScheduleServiceContract
{
    public function getTeacherExamSchedules ($id_teacher, $term, $study_sessions);

    public function getDepartmentExamSchedules ($id_department, $term, $study_sessions);

    public function updateExamSchedule ($data);
}