<?php

namespace App\Repositories;

use App\Models\OtherDepartment;

class OtherDepartmentRepository implements Contracts\OtherDepartmentRepositoryContract
{
    public function get ($id)
    {
        return OtherDepartment::withUuid()->find($id)->makeVisible(['uuid']);
    }
}