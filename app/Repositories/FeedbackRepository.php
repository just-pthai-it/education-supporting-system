<?php

namespace App\Repositories;

use App\Models\Feedback;

class FeedbackRepository implements Contracts\FeedbackRepositoryContract
{
    public function insert ($feedback)
    {
        Feedback::create($feedback);
    }

    public function findAll ()
    {
        return Feedback::latest('id')->get(['title', 'content', 'feedback_type', 'create_at', 'id_bug']);
    }
}