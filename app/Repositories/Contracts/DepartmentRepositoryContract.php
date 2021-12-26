<?php

namespace App\Repositories\Contracts;

interface DepartmentRepositoryContract
{
    public function findAll ();

    public function get ($id);
}