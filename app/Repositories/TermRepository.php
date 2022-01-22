<?php

namespace App\Repositories;

use App\Models\Term;
use App\Repositories\Abstracts\BaseRepository;

class TermRepository extends BaseRepository implements Contracts\TermRepositoryContract
{
    function model () : string
    {
        return Term::class;
    }
}