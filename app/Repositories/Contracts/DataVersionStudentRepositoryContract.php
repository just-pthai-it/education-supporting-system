<?php

namespace App\Repositories\Contracts;

interface DataVersionStudentRepositoryContract
{
    public function insert ($data);

    public function upsertMultiple ($data);

    public function get ($id_student);

    public function getSingleColumn1 ($id_account, $column_name);

    public function update ($id_student, $column_name);

    public function updateMultiple ($id_students, $column_name);
}
