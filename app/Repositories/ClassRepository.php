<?php

namespace App\Repositories;

use App\Models\Class_;
use App\Repositories\Abstracts\BaseRepository;

class ClassRepository extends BaseRepository implements Contracts\ClassRepositoryContract
{
    public function model () : string
    {
        return Class_::class;
    }
}
