<?php

namespace App\Repositories;

use App\Models\AcademicYear;
use App\Repositories\Contracts\AcademicYearRepositoryContract;

class AcademicYearRepository implements AcademicYearRepositoryContract
{
    public function getAcademicYears1 ()
    {
        return AcademicYear::orderBy('id', 'desc')->limit(9)->pluck('academic_year', 'id');
    }

    public function getAcademicYears2 ()
    {
        return AcademicYear::orderBy('id', 'desc')->limit(9)->pluck('id', 'academic_year');
    }
}