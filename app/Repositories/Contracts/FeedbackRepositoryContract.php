<?php

namespace App\Repositories\Contracts;

interface FeedbackRepositoryContract
{
    public function insert ($feedback);

    public function findAll ();
}