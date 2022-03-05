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

    public function create ($feedback)
    {
        $this->feedbackRepository->insert(array_merge($feedback,
                                                      ['id_account' => auth()->user()->id]));
    }

    public function getAll (array $inputs)
    {
        return $this->feedbackRepository->paginate(['id', 'title', 'content', 'feedback_type', 'create_at', 'is_bug'],
                                                   [], [], $inputs['pagination'] ?? 20,
                                                   [['filter', $inputs]]);
    }
}