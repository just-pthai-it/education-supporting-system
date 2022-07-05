<?php

namespace App\Repositories;

use App\Models\PasswordReset;
use App\Repositories\Abstracts\BaseRepository;

class PasswordResetRepository extends BaseRepository implements Contracts\PasswordResetRepositoryContract
{
    function model () : string
    {
        return PasswordReset::class;
    }
}