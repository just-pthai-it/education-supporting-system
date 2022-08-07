<?php

namespace App\Repositories;

use App\Models\Tag;
use App\Repositories\Abstracts\BaseRepository;

class TagRepository extends BaseRepository implements Contracts\TagRepositoryContract
{
    function model () : string
    {
        return Tag::class;
    }
}