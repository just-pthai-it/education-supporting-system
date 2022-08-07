<?php

namespace App\Repositories\Contracts;

interface NotificationRepositoryContract extends BaseRepositoryContract
{
    public function findByIdAccount (string $idAccount, array $inputs);
}
