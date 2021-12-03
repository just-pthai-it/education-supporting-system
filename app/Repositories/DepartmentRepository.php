<?php

namespace App\Repositories;

use App\Models\Department;

class DepartmentRepository implements Contracts\DepartmentRepositoryContract
{
    public function get ($id)
    {
        return Department::withUuid()->find($id)->makeVisible(['uuid']);
    }
}
