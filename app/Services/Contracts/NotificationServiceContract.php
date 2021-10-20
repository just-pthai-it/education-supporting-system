<?php

namespace App\Services\Contracts;

interface NotificationServiceContract
{
    public function pushFCNotification ($notification, $classes);

    public function pushMCNotification ($notification, $classes);

    public function setDelete ($id_sender, $id_notifications);

    public function getReceivedNotifications ($id_account, $offset);

    public function getSentNotifications ($id_sender, $offset);
}
