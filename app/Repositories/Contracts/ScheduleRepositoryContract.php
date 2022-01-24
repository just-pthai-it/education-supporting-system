<?php

namespace App\Repositories\Contracts;

interface ScheduleRepositoryContract extends BaseRepositoryContract
{
    public function findAllByIdTeacher ($id_teacher, $start, $end);

    public function findAllByIdDepartment ($id_department, $start, $end);
}