<?php

namespace App\Repositories\Contracts;

interface ExamScheduleRepositoryContract
{
    public function upsertMultiple ($exam_schedules);

    public function insertPivot ($id_module_class, $id_teachers);

    public function findByIdTeacher ($id_teacher, $start, $end);

    public function findByIdDepartment ($id_department, $start, $end);

    public function update ($new_exam_schedule);
}