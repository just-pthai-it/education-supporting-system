<?php

namespace App\Services\Contracts;

interface NotificationServiceContract
{
    public function pushFCNotification ($notification, $class_list);

    public function pushMCNotification ($notification, $class_list);

    public function setDelete ($id_sender, $id_notifications);

    public function getReceivedNotifications ($id_account, $offset);

    public function getSentNotifications ($id_sender, $offset);
}
