<?php

namespace App\Repositories;

use App\Models\FcmRegistrationToken;
use App\Repositories\Abstracts\BaseRepository;

class FcmRegistrationTokenRepository extends BaseRepository implements Contracts\FcmRegistrationtokenRepositoryContract
{
    function model () : string
    {
        return FcmRegistrationToken::class;
    }


}