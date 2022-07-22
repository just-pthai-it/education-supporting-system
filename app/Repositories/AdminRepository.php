<?php

namespace App\Repositories;

use App\Models\Admin;
use App\Repositories\Abstracts\BaseRepository;

class AdminRepository extends BaseRepository implements Contracts\AdminRepositoryContract
{
    function model () : string
    {
        return Admin::class;
    }
}