<?php

namespace App\Repositories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Abstracts\BaseRepository;

class AccountRepository extends BaseRepository implements Contracts\AccountRepositoryContract
{
    function model () : string
    {
        return Account::class;
    }

    public function findUnreadNotificationsByIdAccount (string $idAccount): Collection
    {
        return $this->model->find($idAccount)->notificationsReceived()
                           ->orderBy('account_notification.id', 'desc')
                           ->whereNull('read_at')->get(['notifications.id']);
    }
}
