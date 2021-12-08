<?php

namespace App\Repositories\Contracts;

interface ExamScheduleRepositoryContract
{
    public function insertMultiple ($exam_schedules);

    public function insertPivot ($id_module_class, $id_teachers);

    public function findAllByIdTeacher ($id_teacher);

    public function update ($data);
}