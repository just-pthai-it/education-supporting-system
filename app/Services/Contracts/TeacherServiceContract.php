<?php

namespace App\Services\Contracts;

interface TeacherServiceContract
{
    public function get ($id_teacher);

    public function getSchedulesByDate ($id_teacher, $start, $end, $shift);

    public function getExamSchedulesByDate ($id_teacher, $start, $end);

    public function getFixedSchedulesByStatus ($id_teacher, $status);

    public function getModuleClassesByStudySessions ($id_teacher, $term, $study_sessions);
}