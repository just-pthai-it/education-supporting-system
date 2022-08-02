<?php

namespace App\Services\Contracts;

interface NotificationServiceContract
{
    public function store (array $inputs);

    public function readManyByIdAccountAndUuidAccount (array $inputs);

    public function markNotificationAsRead (string $idNotification);

    public function markNotificationsAsRead ();
}
