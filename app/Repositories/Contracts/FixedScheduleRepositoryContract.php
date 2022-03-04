<?php

namespace App\Repositories\Contracts;

interface FixedScheduleRepositoryContract extends BaseRepositoryContract
{
    public function findByIdDepartment ($id_department, array $inputs);

    public function findByIdTeacher ($id_teacher, array $inputs);
}
