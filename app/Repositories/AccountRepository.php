<?php

namespace App\Repositories;

use App\Models\Account;
use App\Helpers\GFunction;
use Illuminate\Support\Facades\DB;
use App\Repositories\Abstracts\BaseRepository;

class AccountRepository extends BaseRepository implements Contracts\AccountRepositoryContract
{
    function model () : string
    {
        return Account::class;
    }

    public function updatePassword ($id_account, $password)
    {
        Account::find($id_account)->update(['password' => $password]);
    }

    public function findNotifications (string $uuidAccount, array $inputs)
    {
        $this->createModel();
        return $this->model->where('uuid', '=', GFunction::uuidToBin($uuidAccount))
                           ->with(
                               ['notifications' => function ($query) use ($inputs)
                               {
                                   $query->orderBy('id', 'desc')->select('id', 'data', 'type',
                                                                         'created_at', 'updated_at',
                                                                         DB::raw('notifications.id_account'));
                                   if (isset($inputs['page'], $inputs['pagination']))
                                   {
                                       $inputs['page'] = $inputs['page'] < 1 ? 1 : $inputs['page'];
                                       $query->limit($inputs['pagination'])
                                             ->offset(($inputs['page'] - 1) *
                                                      $inputs['pagination']);
                                   }
                               },
                                'notifications.account:id,accountable_type,accountable_id',
                                'notifications.account.accountable:id,name']
                           )->get(['id']);
    }


}
