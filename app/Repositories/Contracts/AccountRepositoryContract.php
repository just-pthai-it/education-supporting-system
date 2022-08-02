<?php

namespace App\Repositories\Contracts;

interface AccountRepositoryContract extends BaseRepositoryContract
{
    public function findUnreadNotificationsByIdAccount (string $idAccount);
}
