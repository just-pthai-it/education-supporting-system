<?php

namespace App\Repositories\Contracts;

interface FacultyRepositoryContract
{
    public function get ($id_account);

    public function getIDFaculties ($data);
}