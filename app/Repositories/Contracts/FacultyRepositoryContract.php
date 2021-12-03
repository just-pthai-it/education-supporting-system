<?php

namespace App\Repositories\Contracts;

interface FacultyRepositoryContract
{
    public function get ($id);

    public function getIDFaculties ($data);
}