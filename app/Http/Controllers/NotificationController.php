<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidFormRequestException;
use App\Http\FormRequest\PushNotificationForm;
use App\Services\Contracts\NotificationServiceContract;
use App\Services\Contracts\NotifyServiceContract;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    private PushNotificationForm $form;
    private NotificationServiceContract $notificationService;
    private NotifyServiceContract $notifyService;

    /**
     * PushNotificationBMCController constructor.
     * @param PushNotificationForm $pushNotificationForm
     * @param NotificationServiceContract $notificationService
     * @param NotifyServiceContract $notifyService
     */
    public function __construct (PushNotificationForm        $pushNotificationForm,
                                 NotificationServiceContract $notificationService,
                                 NotifyServiceContract       $notifyService)
    {
        $this->form                = $pushNotificationForm;
        $this->notificationService = $notificationService;
        $this->notifyService       = $notifyService;
    }

    /**
     * @throws InvalidFormRequestException
     */
    public function pushFCNotification (Request $request)
    {
        $this->form->validate($request);
        $id_account_list = $this->notificationService->pushFCNotification($request->info, $request->class_list);
        $this->notifyService->sendNotification($request->info, $id_account_list);
    }

    /**
     * @throws InvalidFormRequestException
     */
    public function pushMCNotification (Request $request)
    {
        $this->form->validate($request);
        $id_account_list = $this->notificationService->pushMCNotification($request->info, $request->class_list);

        $this->notifyService->sendNotification($request->info, $id_account_list);
    }

    public function getSentNotifications ($id_sender, $offset = '0')
    {
        $data = $this->notificationService->getSentNotifications($id_sender, $offset);
        return response($data, 200);
    }

    public function getReceivedNotifications ($id_account, $offset)
    {
        $data = $this->notificationService->getReceivedNotifications($id_account, $offset);
        return response($data, 200);
    }

    public function deleteNotifications (Request $request)
    {
        $this->notificationService->setDelete($request->id_sender, $request->id_notifications);
    }
}
