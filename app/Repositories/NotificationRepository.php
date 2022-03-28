<?php

namespace App\Repositories;

    use App\Models\Notification;
use App\Repositories\Abstracts\BaseRepository;

class NotificationRepository extends BaseRepository implements Contracts\NotificationRepositoryContract
{
    function model () : string
    {
        return Notification::class;
    }
}
