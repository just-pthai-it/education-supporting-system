<?php

namespace App\Repositories;

use App\Models\StudySession;
use App\Repositories\Abstracts\BaseRepository;

class StudySessionRepository extends BaseRepository implements Contracts\StudySessionRepositoryContract
{
    function model () : string
    {
        return StudySession::class;
    }
}