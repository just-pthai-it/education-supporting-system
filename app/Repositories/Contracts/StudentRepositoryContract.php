<?php

namespace App\Repositories\Contracts;

interface StudentRepositoryContract
{
    public function insert ($data);

    public function upsertMultiple ($data);

    public function get ($id_account);

    public function getIDStudents1 ($classes);

    public function getIDStudents2 ($id_accounts);

    public function updateMultiple ($id_students);
}
