<?php

namespace App\Repositories\Contracts;

interface CurriculumRepositoryContract
{
    public function insertGetId ($curriculum);

    public function insertPivot ($id_curriculum, $id_modules);
}