<?php

namespace App\Repositories;

use App\Models\Account;
use App\Repositories\Contracts\DepartmentRepositoryContract;

class DepartmentRepository implements DepartmentRepositoryContract
{
    public function get ($id_account)
    {
        return Account::find($id_account)->department;
    }
}
