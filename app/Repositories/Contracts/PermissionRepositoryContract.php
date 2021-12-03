<?php

namespace App\Repositories\Contracts;

interface PermissionRepositoryContract
{
    public function findByIdRole (string $id_role);
}