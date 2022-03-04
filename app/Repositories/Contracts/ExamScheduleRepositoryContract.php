<?php

namespace App\Repositories\Contracts;

interface ExamScheduleRepositoryContract extends BaseRepositoryContract
{
    public function findByIdTeacher ($id_teacher, array $inputs);

    public function findByIdDepartment ($id_department, array $inputs);
}