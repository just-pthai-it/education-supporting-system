<?php

namespace App\Repositories;

use App\Models\Account;
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


}
