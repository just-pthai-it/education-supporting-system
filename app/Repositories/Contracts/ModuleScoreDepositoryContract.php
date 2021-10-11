<?php

namespace App\Repositories\Contracts;

interface ModuleScoreDepositoryContract
{
    public function get ($id_student);

    public function insertMultiple ($data);

    public function upsert ($data);
}