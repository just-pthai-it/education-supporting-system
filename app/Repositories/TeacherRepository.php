<?php

namespace App\Repositories;

use App\Models\Teacher;
use App\Repositories\Abstracts\BaseRepository;

class TeacherRepository extends BaseRepository implements Contracts\TeacherRepositoryContract
{
    public function model () : string
    {
        return Teacher::class;
    }
}