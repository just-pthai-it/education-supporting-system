<?php

namespace App\Services\Contracts;

interface DepartmentServiceContract
{
    public function getSchedulesByDate ($id_department, $start, $end);

    public function getExamSchedulesByDate ($id_department, $start, $end);

    public function getFixedSchedulesByStatus ($id_department, $status);

    public function getModuleClassesByStudySessions ($id_department, $term, $study_sessions);

    public function getTeachers ($id_department);
}