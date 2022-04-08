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

    public function create (Request $request)
    {
        $this->feedbackService->create($request->all());
        return response('', 201);
    }

    public function getAll (Request $request)
    {
        return $this->feedbackService->getAll($request->all());
    }
}
