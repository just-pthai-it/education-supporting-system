<?php

namespace App\Services\Contracts;

interface AcademicYearServiceContract
{
    public function getRecentAcademicYears ();

    public function getAcademicYearsWithTrainingType();
}
