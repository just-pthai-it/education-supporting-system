<?php

namespace App\Repositories\Contracts;

interface NotificationAccountRepositoryContract
{
    public function insertMultiple ($data);

    public function getIDAccounts ($id_notification_list);

    public function getIDNotifications ($id_account, $offset);
}
