<?php

namespace App\Repositories;

use App\Models\Curriculum;

class CurriculumRepository implements Contracts\CurriculumRepositoryContract
{
    public function insertGetId ($curriculum)
    {
        return Curriculum::insertGetId($curriculum);
    }

    public function insertPivot ($id_curriculum, $id_modules)
    {
        Curriculum::find($id_curriculum)->modules()->attach($id_modules);
    }
}