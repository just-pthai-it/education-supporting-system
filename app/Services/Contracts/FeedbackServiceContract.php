<?php

namespace App\Services\Contracts;

interface FeedbackServiceContract
{
    public function createFeedback ($feedback);

    public function getAllFeedbacks ();
}