<?php

namespace App\Repositories;

use App\Models\DataVersionTeacher;
use App\Repositories\Abstracts\BaseRepository;

class DataVersionTeacherRepository extends BaseRepository implements Contracts\DataVersionTeacherRepositoryContract
{
    function model () : string
    {
        return DataVersionTeacher::class;
    }


}