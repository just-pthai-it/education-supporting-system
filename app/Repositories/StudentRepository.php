<?php

namespace App\Repositories;

use App\Repositories\Contracts\StudentRepositoryContract;
use App\Models\Account;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class StudentRepository implements StudentRepositoryContract
{
    public function get ($id_account)
    {
        return Account::find($id_account)->student;
    }

    public function getIDStudents ($class_list) : array
    {
        $this->_createTemporaryTable($class_list);

        return DB::table(Student::table_as)
                 ->join('temp', 'stu.id_class', 'temp.id_class')
                 ->pluck('id')
                 ->toArray();
    }

    public function insertMultiple ($data)
    {
        Student::upsert($data, ['id'], ['id']);
    }

    public function updateMultiple ($id_student_list)
    {
        Student::whereIn('student.id', $id_student_list)
               ->join(Account::table_as, 'student.id', '=', 'acc.username')
               ->update(['student.id_account' => DB::raw('acc.id')]);
    }

    public function insert ($data)
    {
        Student::create($data);
    }

    public function _createTemporaryTable ($class_list)
    {
        $sql_query =
            'CREATE TEMPORARY TABLE temp (
                  id_class varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

        DB::unprepared($sql_query);
        DB::table('temp')->insert($class_list);
    }
}
