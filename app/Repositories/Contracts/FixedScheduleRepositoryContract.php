<?php

namespace App\Repositories\Contracts;

interface FixedScheduleRepositoryContract extends BaseRepositoryContract
{
    public function findByIdDepartment ($id_department, array $conditions);

    public function findByIdTeacher ($id_teacher, $status);
}
