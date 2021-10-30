<?php

namespace App\Repositories\Contracts;

interface SchoolYearRepositoryContract
{
    public function insert ($data);

    public function getMultiple ();
}