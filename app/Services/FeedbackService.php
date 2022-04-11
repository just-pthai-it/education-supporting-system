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
        $feedback = array_merge($feedback, ['id_account' => auth()->user()->id]);
        $this->feedbackRepository->insert($feedback);
    }

    public function readMany (array $inputs)
    {
        return $this->feedbackRepository->paginate(['id', 'data', 'type', 'is_bug', 'created_at'],
                                                   [], [], $inputs['pagination'] ?? 20,
                                                   [['filter', $inputs]]);
    }
}