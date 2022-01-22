<?php

namespace App\Repositories\Contracts;

interface RoleRepositoryContract extends BaseRepositoryContract
{
    public function findPermissionsByIdRole (string $id_role);
}