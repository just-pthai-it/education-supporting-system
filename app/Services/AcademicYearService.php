<?php

namespace App\Services;

use App\Repositories\Contracts\AcademicYearRepositoryContract;

class AcademicYearService implements Contracts\AcademicYearServiceContract
{
    private AcademicYearRepositoryContract $academicYearRepository;

    /**
     * @param AcademicYearRepositoryContract $academicYearRepository
     */
    public function __construct (AcademicYearRepositoryContract $academicYearRepository)
    {
        $this->academicYearRepository = $academicYearRepository;
    }

    public function readMany (array $inputs)
    {
        return $this->academicYearRepository->find(['id', 'name'], [], [], [], [['filter', $inputs]]);
    }
}
