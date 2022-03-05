<?php

namespace App\Repositories;

use App\Models\Faculty;
use App\Repositories\Abstracts\BaseRepository;

class FacultyRepository extends BaseRepository implements Contracts\FacultyRepositoryContract
{
    function model () : string
    {
        return Faculty::class;
    }
}
