<?php

namespace App\Repositories;

use App\Models\StudySession;

class StudySessionRepository implements Contracts\StudySessionRepositoryContract
{
    public function findAllRecent1 ()
    {
        return StudySession::orderBy('id', 'desc')->limit(7)
                           ->get(['id as id_study_year', 'name'])->toArray();
    }

    public function findAllRecent2 ()
    {
        return StudySession::orderBy('id', 'desc')->limit(7)->pluck('id')->toArray();
    }
}