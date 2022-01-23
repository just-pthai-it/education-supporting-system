<?php

namespace App\Services;

use App\Repositories\Contracts\CurriculumRepositoryContract;

class CurriculumService implements Contracts\CurriculumServiceContract
{
    private CurriculumRepositoryContract $curriculumRepository;

    /**
     * @param CurriculumRepositoryContract $curriculumRepository
     */
    public function __construct (CurriculumRepositoryContract $curriculumRepository)
    {
        $this->curriculumRepository = $curriculumRepository;
    }

    public function createCurriculumGetId ($curriculum)
    {
        return $this->curriculumRepository->insertGetId($curriculum);
    }
}