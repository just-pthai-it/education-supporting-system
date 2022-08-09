<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateNotificationPostRequest;
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

    public function store (CreateNotificationPostRequest $request)
    {
        return $this->notificationService->store($request->validated());
    }

    public function readManyByIdAccountAndUuidAccount (Request $request)
    {
        return $this->notificationService->readManyByIdAccountAndUuidAccount($request->all());
    }

    public function readManyUnreadByIdAccountAndUuidAccount (Request $request)
    {
        return $this->notificationService->readManyByIdAccountAndUuidAccount($request->all(),
                                                                             true);
    }

    public function markNotificationAsRead (Request $request, string $uuidAccount,
                                            string  $idNotification = '')
    {
        $this->notificationService->markNotificationAsRead($idNotification);
    }

    public function markNotificationsAsRead (Request $request, string $uuidAccount)
    {
        $this->notificationService->markNotificationsAsRead();
    }
}
