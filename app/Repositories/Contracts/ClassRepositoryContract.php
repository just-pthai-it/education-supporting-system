<?php

namespace App\Repositories\Contracts;

interface ClassRepositoryContract
{
    public function upsert ($data);

    public function upsertMultiple ($data);

    public function getClasses ($id_academic_years, $id_faculties);
}
