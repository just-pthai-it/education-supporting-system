<?php

namespace App\Repositories;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Abstracts\BaseRepository;

class NotificationRepository extends BaseRepository implements Contracts\NotificationRepositoryContract
{
    function model () : string
    {
        return Notification::class;
    }

    public function findByIdAccount (string $idAccount, array $inputs)
    {
        $this->model = $this->model->whereHas('accounts',
            function (Builder $query) use ($idAccount)
            {
                $query->orderBy('account_notification.id', 'desc')
                      ->where('id_account', '=', $idAccount);
            })->filter($inputs)->orderBy('id', 'desc')
                                   ->with([
                                              'accounts' => function ($query) use ($idAccount)
                                              {
                                                  $query->select('id_account')
                                                        ->where('id_account', '=', $idAccount);
                                              },
                                              'account:id,accountable_type,accountable_id',
                                              'account.accountable:id,name',
                                          ]);

        if (isset($inputs['page']))
        {
            return $this->model->paginate($inputs['pagination'] ?? 10);
        }
        else
        {
            return $this->model->get();
        }
    }
}
