<?php

namespace App\Repositories;

use App\Models\Permission;
use App\Repositories\Abstracts\BaseRepository;

class PermissionRepository extends BaseRepository implements Contracts\PermissionRepositoryContract
{
    function model () : string
    {
        return Permission::class;
    }
}