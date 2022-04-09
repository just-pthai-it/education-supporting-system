<?php

namespace App\Repositories;

use App\Models\AcademicYear;
use App\Models\TrainingType;
use Illuminate\Support\Facades\DB;

class AcademicYearRepository implements Contracts\AcademicYearRepositoryContract
{
    public function getAcademicYears1 ()
    {
        return AcademicYear::orderBy('id', 'desc')->limit(18)
                           ->get(['id as id_academic_year', 'name']);
    }

    public function getAcademicYears2 ()
    {
        return AcademicYear::orderBy('id', 'desc')->limit(18)->pluck('id', 'name');
    }

    public function getAcademicYears3 ()
    {
        $array  = DB::table(AcademicYear::table_as)
                    ->join(TrainingType::table_as, 'ays.id_training_type', '=', 'tt.id')
                    ->get(['tts.name as trainingType', 'sys.name as academicYear']);
        $result = [];
        $count  = count($array);

        for ($i = 0; $i < $count; $i++)
        {
            $curr = $array[$i];
            $type = $curr->trainingType;
            $year = $curr->academicYear;

            if (!isset($result[$type]))
            {
                $result[$type] = [0 => $year];
            }
            else
            {
                array_push($result[$type], $year);
            }
        }
        return $result;
    }
}
