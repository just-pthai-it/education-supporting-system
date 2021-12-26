<?php

namespace App\Repositories;

use App\Models\Faculty;
use App\Models\Department;

class DepartmentRepository implements Contracts\DepartmentRepositoryContract
{
    public function findAll ()
    {
        return Faculty::with(['value:id,name,id_faculty'])->get(['id', 'name']);
    }

    public function get ($id)
    {
        return Department::withUuid()->find($id)->makeVisible(['uuid']);
    }
}
