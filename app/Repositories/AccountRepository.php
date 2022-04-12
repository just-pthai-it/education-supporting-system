<?php

namespace App\Repositories;

use App\Models\Account;
use App\Repositories\Abstracts\BaseRepository;

class AccountRepository extends BaseRepository implements Contracts\AccountRepositoryContract
{
    function model () : string
    {
        return Account::class;
    }
}
