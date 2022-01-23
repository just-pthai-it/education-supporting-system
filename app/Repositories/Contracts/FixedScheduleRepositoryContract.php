<?php

namespace App\Repositories\Contracts;

interface FixedScheduleRepositoryContract extends BaseRepositoryContract
{
    public function findByStatusAndIdDepartment ($id_department, $status);

    public function findByStatusAndIdTeacher ($id_teacher, $status);
}
