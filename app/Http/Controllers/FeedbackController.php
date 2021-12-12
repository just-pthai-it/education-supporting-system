<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Contracts\FeedbackServiceContract;

class FeedbackController extends Controller
{
    private FeedbackServiceContract $feedbackService;

    /**
     * @param FeedbackServiceContract $feedbackService
     */
    public function __construct (FeedbackServiceContract $feedbackService)
    {
        $this->feedbackService = $feedbackService;
    }

    public function createFeedback (Request $request)
    {
        $this->feedbackService->createFeedback($request->only(['title', 'content', 'feedback_type', 'create_at', 'is_bug']));
    }

    public function getAllFeedbacks ()
    {
        return response($this->feedbackService->getAllFeedbacks());
    }
}
