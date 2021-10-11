<?php

namespace App\Services;

use App\Repositories\Contracts\AcademicYearRepositoryContract;
use App\Services\Contracts\AcademicYearServiceContract;

class AcademicYearService implements AcademicYearServiceContract
{
    private AcademicYearRepositoryContract $academicYearRepository;

    /**
     * @param AcademicYearRepositoryContract $academicYearRepository
     */
    public function __construct (AcademicYearRepositoryContract $academicYearRepository)
    {
        $this->academicYearRepository = $academicYearRepository;
    }

    public function getRecentAcademicYears ()
    {
        return $this->academicYearRepository->getAcademicYears1();
    }
}