<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\NotificationPostRequest;
use App\Services\Contracts\NotificationServiceContract;

class NotificationController extends Controller
{
    private NotificationServiceContract $notificationService;

    /**
     * @param NotificationServiceContract $notificationService
     */
    public function __construct (NotificationServiceContract $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function store (NotificationPostRequest $request)
    {
        $this->notificationService->store($request->validated());
        return response('', 201);
    }
}
