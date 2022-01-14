<?php

namespace App\Repositories\Contracts;

interface ScheduleRepositoryContract
{
    public function insertMultiple ($data);

    public function update ($object, $column = 'id', $operator = '=');

    public function findById ($id, $columns = ['*']);

    public function findAllByIdTeacher ($id_teacher, $start, $end);

    public function findAllByIdDepartment ($id_department, $start, $end);
}