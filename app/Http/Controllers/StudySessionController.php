<?php

namespace App\Http\Controllers;

use App\Services\Contracts\StudySessionServiceContract;
use Illuminate\Http\Request;

class StudySessionController extends Controller
{
    private StudySessionServiceContract $studySessionService;

    /**
     * @param StudySessionServiceContract $studySessionService
     */
    public function __construct (StudySessionServiceContract $studySessionService)
    {
        $this->studySessionService = $studySessionService;
    }

    public function getRecentStudySessions ()
    {
        return $this->studySessionService->getRecentStudySessions();
    }
}
