<?php

namespace App\Services;

use App\Repositories\Contracts\FeedbackRepositoryContract;

class FeedbackService implements Contracts\FeedbackServiceContract
{
    private FeedbackRepositoryContract $feedbackRepository;

    /**
     * @param FeedbackRepositoryContract $feedbackRepository
     */
    public function __construct (FeedbackRepositoryContract $feedbackRepository)
    {
        $this->feedbackRepository = $feedbackRepository;
    }

    public function createFeedback ($feedback)
    {
        $feedback['id_account'] = auth()->user()->id;
        $this->feedbackRepository->insert($feedback);
    }

    public function getAllFeedbacks ()
    {
        return $this->feedbackRepository->find(['title', 'content', 'feedback_type', 'create_at', 'is_bug'],
                                               [], [['id', 'desc']]);
    }
}