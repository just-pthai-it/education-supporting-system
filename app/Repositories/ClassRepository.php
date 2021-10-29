<?php

namespace App\Repositories;

use App\Models\Class_;
use Illuminate\Support\Collection;

class ClassRepository implements Contracts\ClassRepositoryContract
{
    public function insert ($data)
    {
        Class_::insert($data);
    }

    public function insertMultiple ($data)
    {
        Class_::insert($data);
    }

    public function getClasses ($id_academic_years, $id_faculties) : Collection
    {
        return Class_::whereIn('id_academic_year', $id_academic_years)
                     ->whereIn('id_faculty', $id_faculties)
                     ->orderBy('id_academic_year')
                     ->orderBy('id_faculty')
                     ->orderBy('id')
                     ->select('id_academic_year', 'id_faculty', 'id as id_class')->get();
    }
}
