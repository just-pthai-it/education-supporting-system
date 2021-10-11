<?php

namespace App\Repositories;

use App\Models\ExamSchedule;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Repositories\Contracts\ExamScheduleRepositoryContract;
use Illuminate\Support\Collection;

class ExamScheduleRepository implements ExamScheduleRepositoryContract
{
    public function get ($id_student) : Collection
    {
        return Student::find($id_student)->examSchedules()
                      ->orderBy('date_start')
                      ->select('id as id_exam_schedule', 'school_year', 'module_name',
                               'credit', 'date_start', 'time_start',
                               'method', 'identification_number', 'room')
                      ->get();
    }

    public function getLatestSchoolYear ($id_student)
    {
        return SchoolYear::whereHas('examSchedules', function ($query)
        {
            return $query->where('id_student', '191201402');
        })->orderBy('id', 'desc')->limit(1)->select('id', 'school_year')->get()->toArray();
    }

    public function insertMultiple ($data)
    {
        ExamSchedule::insert($data);
    }

    public function upsert ($data)
    {
        ExamSchedule::upsert($data,
                             ['school_year', 'id_module_class', 'id_student'],
                             [
                                 'date_start', 'time_start', 'method',
                                 'identification_number', 'room'
                             ]);
    }

    public function delete ($id_student, $id_school_year)
    {
        ExamSchedule::where('id_student', '=', $id_student)
                    ->where('id_school_year', '=', $id_school_year)
                    ->delete();
    }
}
