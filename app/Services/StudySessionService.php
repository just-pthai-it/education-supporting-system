<?php

namespace App\Services;

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

    public function getRecentStudySessions ()
    {
        return $this->studySessionRepository->getMultiple();
    }
}