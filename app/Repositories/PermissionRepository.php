<?php

namespace App\Repositories;

use App\Models\Role;

class PermissionRepository implements Contracts\PermissionRepositoryContract
{

    public function findByIdRole (string $id_role)
    {
        return Role::find($id_role)->permissions()->pluck('id');
    }
}