<?php

namespace App\Repositories;

use App\Models\Account;

class DepartmentRepository implements Contracts\DepartmentRepositoryContract
{
    public function get ($id_account)
    {
        return Account::find($id_account)->department;
    }
}
