<?php

namespace App\Repositories;

use App\Models\Major;
use App\Repositories\Abstracts\BaseRepository;

class MajorRepository extends BaseRepository implements Contracts\MajorRepositoryContract
{
    function model () : string
    {
        return Major::class;
    }
}