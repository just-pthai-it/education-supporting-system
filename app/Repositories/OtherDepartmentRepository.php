<?php

namespace App\Repositories;

use App\Models\Account;

class OtherDepartmentRepository implements Contracts\OtherDepartmentRepositoryContract
{
    public function get ($id_account)
    {
        return Account::find($id_account)->otherDepartment;
    }
}