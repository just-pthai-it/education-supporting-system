<?php

namespace App\Repositories\Contracts;

interface AccountRepositoryContract
{
    public function insertGetId ($data);

    public function insertMultiple ($data);

    public function insertPivotMultiple ($id_account, $roles);

    public function get ($username);

    public function getIDAccounts1 ($id_student_list);

    public function getIDAccounts2 ($id_notifications);

    public function getPermissions ($id_account);

    public function updatePassword ($username, $password);
}
