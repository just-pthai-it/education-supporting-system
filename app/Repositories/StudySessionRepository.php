<?php

namespace App\Repositories;

use App\Models\StudySession;

class StudySessionRepository implements Contracts\StudySessionRepositoryContract
{
    public function getMultiple ()
    {
        return StudySession::orderBy('id', 'desc')->limit(7)
                           ->get(['id as id_study_year', 'study_session'])->toArray();
    }
}