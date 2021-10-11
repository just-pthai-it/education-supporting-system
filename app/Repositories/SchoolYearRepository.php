<?php

namespace App\Repositories;

use App\Models\SchoolYear;
use App\Repositories\Contracts\SchoolYearRepositoryContract;

class SchoolYearRepository implements SchoolYearRepositoryContract
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
                         ->limit(15)
                         ->pluck('id', 'school_year')
                         ->toArray();
    }
}