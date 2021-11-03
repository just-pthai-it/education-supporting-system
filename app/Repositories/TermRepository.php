<?php

namespace App\Repositories;

use App\Models\Term;

class TermRepository implements Contracts\TermRepositoryContract
{
    public function insert ($data)
    {
        Term::create($data)->id;
    }

    public function getMultiple ()
    {
        return Term::orderBy('id', 'desc')->limit(14)->pluck('id', 'term')->toArray();
    }
}