<?php

namespace App\Repositories;

use App\Models\AcademicYear;
use App\Models\TrainingType;
use Illuminate\Support\Facades\DB;
use App\Repositories\Abstracts\BaseRepository;

class AcademicYearRepository extends BaseRepository implements Contracts\AcademicYearRepositoryContract
{
    public function model () : string
    {
        return AcademicYear::class;
    }
}
