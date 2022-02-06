<?php

namespace App\Repositories\Contracts;

interface ScheduleRepositoryContract extends BaseRepositoryContract
{
    public function findAllByIdTeacherAndDate ($id_teacher, $start, $end);

    public function findAllByIdDepartmentAndDate ($id_department, $start, $end);

    public function findTeacherEmailByIdSchedule (int $id_schedule);
}