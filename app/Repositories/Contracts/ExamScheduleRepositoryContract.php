<?php

namespace App\Repositories\Contracts;

interface ExamScheduleRepositoryContract extends BaseRepositoryContract
{
    public function findByIdTeacher ($id_teacher, $start, $end);

    public function findByIdDepartment ($id_department, $start, $end);
}