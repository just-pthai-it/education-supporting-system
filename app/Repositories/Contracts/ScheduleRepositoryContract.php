<?php

namespace App\Repositories\Contracts;

interface ScheduleRepositoryContract
{
    public function insertMultiple ($data);

    public function findAllByIdTeacher (string $id_teacher, array $id_study_sessions);

    public function findAllByIdDepartment (string $id_department, array $id_study_sessions);
}