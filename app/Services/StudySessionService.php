<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Repositories\Contracts\StudySessionRepositoryContract;

class StudySessionService implements Contracts\StudySessionServiceContract
{
    private StudySessionRepositoryContract $studySessionRepository;

    /**
     * @param StudySessionRepositoryContract $studySessionRepository
     */
    public function __construct (StudySessionRepositoryContract $studySessionRepository)
    {
        $this->studySessionRepository = $studySessionRepository;
    }

    public function readMany (array $inputs)
    {
        return $this->studySessionRepository->find(['id', 'name'], [], [], [], [['filter', $inputs]]);
    }
}