<?php

namespace App\Repositories\Contracts;

interface FacultyRepositoryContract extends BaseRepositoryContract
{
    public function findAllWithDepartments ();
}