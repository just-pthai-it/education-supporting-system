<?php

namespace App\Repositories\Contracts;

interface StudentRepositoryContract
{
    public function get ($id_account);

    public function getIDStudents ($class_list);

    public function insert ($data);

    public function insertMultiple ($data);

    public function updateMultiple ($id_student_list);
}
