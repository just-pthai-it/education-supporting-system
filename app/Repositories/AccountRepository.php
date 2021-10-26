<?php

namespace App\Repositories;

use App\Models\Account;
use Illuminate\Support\Facades\DB;

class AccountRepository implements Contracts\AccountRepositoryContract
{
    public function insertMultiple ($data)
    {
        Account::insert($data);
    }

    public function insertPivotMultiple ($id_account, $roles)
    {
        Account::find($id_account)->roles()->attach($roles);
    }

    public function getIDAccounts1 ($id_students) : array
    {
        $this->_createTemporaryTable($id_students);
        return DB::table(Account::table_as)
                 ->join('temp1', 'acc.username', '=', 'temp1.id_student')
                 ->pluck('id')
                 ->toArray();
    }

    public function getIDAccounts2 ($id_notifications)
    {
        return Account::whereHas('notifications', function ($query) use ($id_notifications)
        {
            return $query->whereIn('id_notification', $id_notifications);
        })->pluck('id')->toArray();
    }

    public function getPermissions ($id_account)
    {
        return Account::find($id_account)->roles()->pluck('role.id')->toArray();
    }

    public function updatePassword ($username, $password)
    {
        Account::where('username', '=', $username)
               ->update(['password' => $password]);
    }

    public function _createTemporaryTable ($id_students)
    {
        $sql_query =
            'CREATE TEMPORARY TABLE temp1 (
                  id_student varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

        DB::unprepared($sql_query);
        DB::table('temp1')->insert($id_students);
    }
}
