<?php

namespace App\Repositories;

use App\Models\Account;
use App\Models\DataVersionStudent;

class DataVersionStudentRepository implements Contracts\DataVersionStudentRepositoryContract
{
    public function insertMultiple ($data)
    {
        DataVersionStudent::insert($data);
    }

    public function get ($id_student)
    {
        return DataVersionStudent::select('schedule', 'notification',
                                          'module_score', 'exam_schedule')->find($id_student);
    }

    public function getSingleColumn1 ($id_account, $column_name)
    {
        return Account::find($id_account)->dataVersionStudent()->pluck($column_name)->first();
    }

    public function getSingleColumn2 ($id_student, $column_name)
    {
        return DataVersionStudent::select($column_name)->find($id_student)->$column_name;

    }

    public function update ($id_student, $column_name)
    {
        DataVersionStudent::find($id_student)->increment($column_name);
    }

    public function updateMultiple ($id_students, $column_name)
    {
        DataVersionStudent::whereIn('id_student', $id_students)->increment($column_name);
    }
}
