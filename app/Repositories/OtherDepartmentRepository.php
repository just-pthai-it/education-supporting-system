<?php

namespace App\Repositories;

use App\Repositories\Contracts\OtherDepartmentRepositoryContract;
use App\Models\Account;

class OtherDepartmentRepository implements OtherDepartmentRepositoryContract
{
    public function get ($id_account)
    {
        return Account::find($id_account)->otherDepartment;
    }
}