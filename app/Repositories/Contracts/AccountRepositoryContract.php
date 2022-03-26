<?php

namespace App\Repositories\Contracts;

interface AccountRepositoryContract extends BaseRepositoryContract
{
    public function updatePassword ($id_account, $password);
}
