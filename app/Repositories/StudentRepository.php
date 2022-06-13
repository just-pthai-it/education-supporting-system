<?php

namespace App\Repositories;

use App\Models\Student;
use App\Repositories\Abstracts\BaseRepository;

class StudentRepository extends BaseRepository implements Contracts\StudentRepositoryContract
{
    public function model () : string
    {
        return Student::class;
    }
}
