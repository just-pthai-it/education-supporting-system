<?php

namespace App\Repositories\Contracts;

interface DepartmentRepositoryContract
{
    public function findAllWithDepartments ();

    public function get ($id);
}