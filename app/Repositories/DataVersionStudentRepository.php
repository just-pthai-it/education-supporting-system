<?php

namespace App\Repositories;

use App\Repositories\Contracts\DataVersionStudentRepositoryContract;
use App\Models\Account;
use App\Models\DataVersionStudent;
use Illuminate\Support\Facades\DB;

class DataVersionStudentRepository implements DataVersionStudentRepositoryContract
{
    public function insert ($data)
    {
        DataVersionStudent::create($data);
    }

    public function get ($id_student)
    {
        return DataVersionStudent::select('schedule', 'notification', 'module_score', 'exam_schedule')
                                 ->find($id_student);
    }

    public function getSingleColumn1 ($id_account, $column_name)
    {
        return Account::find($id_account)->dataVersionStudent()
                      ->pluck($column_name)->first();
    }

    public function getSingleColumn2 ($id_student, $column_name)
    {
        return DataVersionStudent::where('id_student', '=', $id_student)
                                 ->pluck($column_name)->first();
    }

    public function updateDataVersion ($id_student, $column_name)
    {
        DataVersionStudent::find($id_student)->increment($column_name);
    }

    public function updateMultiple ($id_student_list, $column_name)
    {
        DataVersionStudent::whereIn('id_student', $id_student_list)
                          ->increment($column_name);
    }

    public function upsertMultiple ($data)
    {
        DataVersionStudent::upsert($data,
                                   ['id_student'],
                                   ['schedule' => DB::raw('schedule + 1')]);
    }
}
