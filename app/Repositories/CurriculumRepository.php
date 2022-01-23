<?php

namespace App\Repositories;

use App\Models\Curriculum;
use App\Repositories\Abstracts\BaseRepository;

class CurriculumRepository extends BaseRepository implements Contracts\CurriculumRepositoryContract
{
    public function model () : string
    {
        return Curriculum::class;
    }
}