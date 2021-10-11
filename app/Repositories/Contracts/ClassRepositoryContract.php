<?php

namespace App\Repositories\Contracts;

interface ClassRepositoryContract
{
    public function getFacultyClass ($id_academic_years, $id_faculties);

    public function insertMultiple ($data);

    public function insert ($data);
}
