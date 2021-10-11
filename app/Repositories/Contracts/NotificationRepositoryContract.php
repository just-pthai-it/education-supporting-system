<?php

namespace App\Repositories\Contracts;

interface NotificationRepositoryContract
{
    public function insertGetID ($data);

    public function setDelete ($id_sender, $id_notifications);

    public function getNotifications1 ($id_sender, $num);

    public function getNotifications2 ($id_notifications);

    public function getDeletedNotifications ();
}
