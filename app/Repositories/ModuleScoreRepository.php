<?php

namespace App\Repositories;

use App\Models\ModuleScore;
use App\Models\Student;
use App\Repositories\Contracts\ModuleScoreDepositoryContract;
use Illuminate\Support\Collection;

class ModuleScoreRepository implements ModuleScoreDepositoryContract
{
    public function get ($id_student) : Collection
    {
        return Student::find($id_student)->moduleScores()
                      ->orderBy('school_year')
                      ->select('id as id_module_score', 'school_year', 'module_name',
                               'credit', 'evaluation', 'process_score',
                               'test_score', 'final_score')
                      ->get();
    }

    public function insertMultiple ($data)
    {
        ModuleScore::insert($data);
    }

    public function upsert ($data)
    {
        ModuleScore::upsert($data,
                            ['school_year', 'id_module_class', 'id_student'],
                            [
                                'evaluation', 'process_score',
                                'test_score', 'final_score'
                            ]);
    }
}
