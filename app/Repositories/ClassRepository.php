<?php

namespace App\Repositories;

use App\Models\AcademicYear;
use App\Models\Class_;
use App\Repositories\Contracts\ClassRepositoryContract;
use Illuminate\Support\Collection;

class ClassRepository implements ClassRepositoryContract
{
    public function getFacultyClass ($id_academic_years, $id_faculties) : Collection
    {
        return Class_::whereIn('id_academic_year', $id_academic_years)
                     ->whereIn('id_faculty', $id_faculties)
                     ->orderBy('id_academic_year')
                     ->orderBy('id_faculty')
                     ->orderBy('id')
                     ->select('id_academic_year', 'id_faculty', 'id as id_class')
                     ->get();
    }

    public function insertMultiple ($data)
    {
        Class_::upsert($data, ['id'], ['id']);
    }

    public function insert ($data)
    {
        Class_::updateOrCreate(['id' => $data['id']], $data);
    }
}
