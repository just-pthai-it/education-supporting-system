<?php

namespace App\Repositories\Contracts;

interface AccountRepositoryContract
{
    public function insertMultiple ($data);

    public function insertPivotMultiple ($id_account, $roles);

    public function getIDAccounts1 ($id_students);

    public function getIDAccounts2 ($id_notifications);

    public function getPermissions ($id_account);

    public function updatePassword ($id_account, $password);
}
