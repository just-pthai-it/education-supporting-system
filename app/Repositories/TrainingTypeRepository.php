<?php

namespace App\Repositories;

use App\Models\TrainingType;
use App\Repositories\Abstracts\BaseRepository;

class TrainingTypeRepository extends BaseRepository implements Contracts\TrainingTypeRepositoryContract
{
    function model () : string
    {
        return TrainingType::class;
    }
}