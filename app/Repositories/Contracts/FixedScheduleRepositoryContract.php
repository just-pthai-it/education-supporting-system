<?php

namespace App\Repositories\Contracts;

interface FixedScheduleRepositoryContract extends BaseRepositoryContract
{
    public function paginateByStatusAndIdDepartment ($id_department, $status);

    public function paginateByStatusAndIdTeacher ($id_teacher, $status);
}
