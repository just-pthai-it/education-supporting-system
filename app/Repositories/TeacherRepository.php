<?php

namespace App\Repositories;

use App\Models\Account;
use App\Repositories\Contracts\TeacherRepositoryContract;

class TeacherRepository implements TeacherRepositoryContract
{
    public function get ($id_account)
    {
        return Account::find($id_account)->teacher;
    }
}