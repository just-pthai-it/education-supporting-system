<?php

namespace App\Repositories;

use App\Models\AcademicYear;

class AcademicYearRepository implements Contracts\AcademicYearRepositoryContract
{
    public function getAcademicYears1 ()
    {
        return AcademicYear::orderBy('id', 'desc')->limit(18)
                           ->get(['id as id_academic_year', 'name']);
    }

    public function getAcademicYears2 ()
    {
        return AcademicYear::orderBy('id', 'desc')->limit(18)->pluck('id', 'name');
    }
}