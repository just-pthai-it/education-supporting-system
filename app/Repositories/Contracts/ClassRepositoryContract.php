<?php

namespace App\Repositories\Contracts;

interface ClassRepositoryContract
{
    public function insert ($data);

    public function insertMultiple ($data);

    public function getClasses ($id_academic_years, $id_faculties);
}
