<?php

namespace App\Services\Contracts;

interface DepartmentServiceContract
{
    public function getSchedules ($id_department, $start, $end);

    public function getExamSchedules ($id_department, $start, $end);

    public function getFixedSchedulesByStatus ($id_department, $status);

    public function getModuleClassesByStudySessions ($id_department, $term, $study_sessions);

    public function getTeachers ($id_department);
}