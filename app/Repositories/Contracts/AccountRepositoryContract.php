<?php

namespace App\Repositories\Contracts;

interface AccountRepositoryContract
{
    public function insertGetId ($data);

    public function insertMultiple ($data);

    public function get ($username);

    public function getIDAccounts ($id_student_list);

    public function getPermissions ($id_account);

    public function updatePassword ($username, $password);
}
