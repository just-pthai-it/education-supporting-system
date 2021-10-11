<?php

namespace App\Repositories\Contracts;

interface ParticipateRepositoryContract
{
    public function getIDStudents ($class_list);

    public function insertMultiple ($data);

}
