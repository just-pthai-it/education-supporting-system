<?php

namespace App\Repositories;

use App\Models\Department;
use App\Repositories\Abstracts\BaseRepository;

class DepartmentRepository extends BaseRepository implements Contracts\DepartmentRepositoryContract
{
    function model () : string
    {
        return Department::class;
    }
}
