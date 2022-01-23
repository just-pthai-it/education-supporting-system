<?php

namespace App\Repositories;

use App\Models\Feedback;
use App\Repositories\Abstracts\BaseRepository;

class FeedbackRepository extends BaseRepository implements Contracts\FeedbackRepositoryContract
{
    public function model () : string
    {
        return Feedback::class;
    }
}