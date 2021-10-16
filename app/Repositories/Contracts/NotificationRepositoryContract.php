<?php

namespace App\Repositories\Contracts;

interface NotificationRepositoryContract
{
    public function insertGetID ($data);

    public function insertPivotMultiple ($id_notification, $id_accounts);

    public function getNotifications1 ($id_sender, $num);

    public function getNotifications2 ($id_notifications);

    public function getIDNotifications ($id_account, $offset);

    public function getIDAccounts ($id_notifications);

    public function getDeletedNotifications ();

    public function update ($id_sender, $id_notifications);
}
