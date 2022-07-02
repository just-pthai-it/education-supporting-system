<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreaateNotificationPostRequest;
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

    public function store (CreaateNotificationPostRequest $request)
    {
        return $this->notificationService->store($request->validated());
    }
}
