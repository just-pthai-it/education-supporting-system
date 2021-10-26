<?php

namespace App\Repositories;

use App\Models\Account;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class StudentRepository implements Contracts\StudentRepositoryContract
{
    public function insertMultiple ($data)
    {
        Student::insert($data);
    }

    public function getIDStudents1 ($classes) : array
    {
        $this->_createTemporaryTable1($classes);
        return DB::table(Student::table_as)
                 ->join('temp', 'stu.id_class', 'temp.id_class')
                 ->pluck('id')
                 ->toArray();
    }

    public function getIDStudents2 ($id_module_classes)
    {
        return Student::whereHas('moduleClasses', function ($query) use ($id_module_classes)
        {
            return $query->whereIn('id_module_class', $id_module_classes);
        })->pluck('id')->toArray();
    }

    public function getIDStudents3 ($id_accounts)
    {
        return Student::whereIn('id_account', $id_accounts)->pluck('id')->toArray();
    }

    public function getIDAccounts ($id_students)
    {
        return Student::whereIn('id', $id_students)->pluck('id_account')->toArray();
    }

    public function updateMultiple ($id_students)
    {
        Student::whereIn('student.id', $id_students)
               ->join(Account::table_as, 'student.id', '=', 'acc.username')
               ->update(['student.id_account' => DB::raw('acc.id')]);
    }

    public function _createTemporaryTable1 ($classes)
    {
        $sql_query =
            'CREATE TEMPORARY TABLE temp (
                  id_class varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

        DB::unprepared($sql_query);
        DB::table('temp')->insert($classes);
    }

    public function getIDStudentsMissing ($id_students)
    {
        $this->_createTemporaryTable2($id_students);
        return Student::rightJoin('temp1', 'id', '=', 'id_student')
                      ->whereNull('id')->pluck('id_student')->toArray();
    }

    public function _createTemporaryTable2 ($id_students)
    {
        $sql_query =
            'CREATE TEMPORARY TABLE temp1 (
                id_student varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

        DB::unprepared($sql_query);
        DB::table('temp1')->insert($id_students);
    }
}
