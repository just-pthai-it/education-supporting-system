<?php

namespace App\Repositories\Contracts;

interface TermRepositoryContract
{
    public function insert ($data);

    public function getMultiple ();
}