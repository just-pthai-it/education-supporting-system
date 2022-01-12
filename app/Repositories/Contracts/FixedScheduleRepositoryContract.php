<?php

namespace App\Repositories\Contracts;

interface FixedScheduleRepositoryContract
{
    public function insert ($fixed_schedule);

    public function update ($object, $column = 'id', $operator = '=');

    public function findByStatusAndIdDepartment ($id_department, $status);

    public function findByStatusAndIdTeacher ($id_teacher, $status);
}
