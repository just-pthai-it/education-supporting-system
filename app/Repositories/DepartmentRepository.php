<?php

namespace App\Repositories;

use App\Models\Faculty;
use App\Models\Department;

class DepartmentRepository implements Contracts\DepartmentRepositoryContract
{
    public function findAllWithDepartments ()
    {
        return Faculty::with(['departments:id,name,id_faculty'])->get(['id', 'name']);
    }

    public function get ($id)
    {
        return Department::withUuid()->find($id)->makeVisible(['uuid']);
    }
}
