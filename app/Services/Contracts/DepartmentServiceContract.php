<?php

namespace App\Services\Contracts;

interface DepartmentServiceContract
{
    public function getSchedules ($id_department, array $inputs);

    public function getExamSchedulesByDate ($id_department, $start, $end);

    public function getFixedSchedules ($id_department, array $inputs);

    public function getModuleClassesByStudySessions ($id_department, $term, $study_sessions);

    public function getTeachers ($id_department);
}