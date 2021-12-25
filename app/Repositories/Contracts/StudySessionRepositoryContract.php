<?php

namespace App\Repositories\Contracts;

interface StudySessionRepositoryContract
{
    public function findRecent ();

    public function findByNames ($study_sessions);
}