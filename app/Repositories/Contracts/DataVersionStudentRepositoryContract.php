<?php

namespace App\Repositories\Contracts;

interface DataVersionStudentRepositoryContract
{
    public function insert ($data);

    public function get ($id_student);

    public function getSingleColumn1 ($id_account, $column_name);

    public function updateDataVersion ($id_student, $column_name);

    public function updateMultiple ($id_student_list, $column_name);

    public function upsertMultiple ($data);
}
