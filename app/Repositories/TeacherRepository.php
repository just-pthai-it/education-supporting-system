<?php

namespace App\Repositories;

use App\Models\Teacher;

class TeacherRepository implements Contracts\TeacherRepositoryContract
{
    public function findById ($id)
    {
        return Teacher::withUuid()->find($id)->makeVisible(['uuid']);
    }

    public function findByIdDepartment ($id_department)
    {
        return Teacher::where('id_department', '=', $id_department)->pluck('id', 'name')->toArray();
    }

    public function findByIdDepartment2 ($id_department)
    {
        return Teacher::where('id_department', '=', $id_department)->get(['id', 'name'])->toArray();
    }
}