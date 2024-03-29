<?php

namespace App\Repositories;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Abstracts\BaseRepository;

class NotificationRepository extends BaseRepository implements Contracts\NotificationRepositoryContract
{
    function model () : string
    {
        return Notification::class;
    }

    public function findByIdAccount (string $idAccount, array $inputs,
                                     bool   $isOnlyUnread = false) : Collection
    {
        return $this->model->whereHas('accounts',
            function (Builder $query) use ($idAccount, $isOnlyUnread)
            {
                $query->orderBy('account_notification.id', 'desc')
                      ->where('id_account', '=', $idAccount)
                      ->when($isOnlyUnread, function (Builder $query)
                      {
                          $query->whereNull('read_at');
                      });
            })->orderBy('id', 'desc')->filter($inputs)
                           ->with([
                                      'accounts' => function ($query) use (
                                          $idAccount
                                      )
                                      {
                                          $query->select('id_account')
                                                ->where('id_account',
                                                        '=',
                                                        $idAccount);
                                      },
                                      'account:id,accountable_type,accountable_id',
                                      'account.accountable:id,name',
                                  ])->get();
    }
}
