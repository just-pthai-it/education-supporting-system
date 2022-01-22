<?php

namespace App\Repositories;

use App\Models\Role;
use App\Repositories\Abstracts\BaseRepository;

class RoleRepository extends BaseRepository implements Contracts\RoleRepositoryContract
{
    function model () : string
    {
        return Role::class;
    }

    public function findPermissionsByIdRole (string $id_role)
    {
        return $this->model->find($id_role)->permissions()->pluck('id');
    }
}