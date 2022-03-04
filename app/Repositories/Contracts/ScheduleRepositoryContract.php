<?php

namespace App\Repositories\Contracts;

interface ScheduleRepositoryContract extends BaseRepositoryContract
{
    public function findAllByIdTeacher ($id_teacher, array $inputs);

    public function findAllByIdDepartment ($id_department, array $inputs);

    public function findTeacherEmailByIdSchedule (int $id_schedule);
}