<?php

namespace App\Repositories;

use App\Models\StudySession;

class StudySessionRepository implements Contracts\StudySessionRepositoryContract
{
    public function findRecent ()
    {
        return StudySession::orderBy('id', 'desc')->limit(7)
                           ->get(['id as id_study_year', 'name'])->toArray();
    }

    public function findByNames ($study_sessions)
    {
        return StudySession::whereIn('name', $study_sessions)->pluck('id')->toArray();
    }
}