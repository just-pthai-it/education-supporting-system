<?php

namespace App\Repositories;

use App\Models\SchoolYear;

class SchoolYearRepository implements Contracts\SchoolYearRepositoryContract
{
    public function insert ($data)
    {
        SchoolYear::create($data)->id;
    }

    public function get ($school_year)
    {
        return SchoolYear::where('school_year', '=', $school_year)
                         ->select()->first();
    }

    public function getMultiple ()
    {
        return SchoolYear::orderBy('school_year', 'desc')
                         ->limit(7)
                         ->select('id as id_school_year', 'school_year')
                         ->toArray();
    }
}