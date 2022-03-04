<?php

namespace App\Services\Contracts;

interface TeacherServiceContract
{
    public function get ($id_teacher);

    public function getSchedules ($id_teacher, array $inputs);

    public function getExamSchedulesByDate ($id_teacher, $start, $end);

    public function getFixedSchedulesByStatus ($id_teacher, array $inputs);

    public function getModuleClassesByStudySessions ($id_teacher, $term, $study_sessions);
}