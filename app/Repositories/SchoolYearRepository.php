<?php

namespace App\Repositories;

use App\Models\SchoolYear;
use App\Repositories\Abstracts\BaseRepository;

class SchoolYearRepository extends BaseRepository implements Contracts\SchoolYearRepositoryContract
{
    function model () : string
    {
        return SchoolYear::class;
    }
}