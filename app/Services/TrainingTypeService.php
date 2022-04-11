<?php

namespace App\Services;

use App\Repositories\Contracts\TrainingTypeRepositoryContract;

class TrainingTypeService implements Contracts\TrainingTypeServiceContract
{
    private TrainingTypeRepositoryContract $trainingTypeRepository;

    /**
     * @param TrainingTypeRepositoryContract $trainingTypeRepository
     */
    public function __construct (TrainingTypeRepositoryContract $trainingTypeRepository)
    {
        $this->trainingTypeRepository = $trainingTypeRepository;
    }

    public function readMany (array $inputs)
    {
        return $this->trainingTypeRepository->find(['*'], [], [], [], [['with', 'academicYears']]);
    }
}