<?php

namespace App\Repositories;

use App\Models\DataVersionStudent;
use App\Repositories\Abstracts\BaseRepository;

class DataVersionStudentRepository extends BaseRepository implements Contracts\DataVersionStudentRepositoryContract
{
    function model () : string
    {
        return DataVersionStudent::class;
    }

        DataVersionStudentRepositoryContract::class    => DataVersionStudentRepository::class,

}