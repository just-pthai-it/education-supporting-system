<?php

namespace App\Repositories;

use App\Repositories\Contracts\AccountRepositoryContract;
use App\Models\Account;
use Illuminate\Support\Facades\DB;

class AccountRepository implements AccountRepositoryContract
{
    public function get ($username) : array
    {
        return Account::where('username', '=', $username)
                      ->select('id', 'username', 'password', 'permission')
                      ->get()
                      ->toArray();
    }

    public function getIDAccounts ($id_student_list) : array
    {
        $this->_createTemporaryTable($id_student_list);

        return DB::table(Account::table_as)
                 ->join('temp1', 'acc.username', '=', 'temp1.id_student')
                 ->pluck('id')
                 ->toArray();
    }

    public function updateQLDTPassword ($username, $qldt_password)
    {
        Account::where('username', '=', $username)
               ->update(['qldt_password' => $qldt_password]);
    }

    public function updatePassword ($username, $password)
    {
        Account::where('username', '=', $username)
               ->update(['password' => $password]);
    }

    public function getQLDTPassword ($id_student)
    {
        return Account::where('username', '=', $id_student)
                      ->pluck('qldt_password')
                      ->first();
    }

    public function insertMultiple ($data)
    {
        Account::upsert($data, ['username'], ['username']);
    }

    public function insertGetId ($data) : int
    {
        return Account::create($data)->id;
    }

    public function _createTemporaryTable ($id_student_list)
    {
        $sql_query =
            'CREATE TEMPORARY TABLE temp1 (
                  id_student varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

        DB::unprepared($sql_query);
        DB::table('temp1')->insert($id_student_list);
    }
}
