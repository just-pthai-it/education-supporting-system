<?php

namespace App\Repositories;

use App\Models\ThirdPartyToken;
use App\Repositories\Abstracts\BaseRepository;

class ThirdPartyTokenRepository extends BaseRepository implements Contracts\ThirdPartyTokenRepositoryContract
{
    function model () : string
    {
        return ThirdPartyToken::class;
    }
}