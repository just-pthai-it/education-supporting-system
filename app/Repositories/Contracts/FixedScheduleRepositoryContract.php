<?php

namespace App\Repositories\Contracts;

interface FixedScheduleRepositoryContract extends BaseRepositoryContract
{
    public function paginateByIdDepartment ($id_department, array $conditions);

    public function paginateByStatusAndIdTeacher ($id_teacher, $status);
}
