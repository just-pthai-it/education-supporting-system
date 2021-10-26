<?php

namespace App\Repositories;

use App\Models\Account;

class TeacherRepository implements Contracts\TeacherRepositoryContract
{
    public function get ($id_account)
    {
        return Account::find($id_account)->teacher;
    }
}