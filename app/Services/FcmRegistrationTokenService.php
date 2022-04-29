<?php

namespace App\Services;

use App\Repositories\Contracts\FcmRegistrationtokenRepositoryContract;

class FcmRegistrationTokenService implements Contracts\FcmRegistrationTokenServiceContract
{
    private FcmRegistrationtokenRepositoryContract $fCMRegistrationTokenDepository;

    /**
     * @param FcmRegistrationtokenRepositoryContract $fCMRegistrationTokenDepository
     */
    public function __construct (FcmRegistrationtokenRepositoryContract $fCMRegistrationTokenDepository)
    {
        $this->fCMRegistrationTokenDepository = $fCMRegistrationTokenDepository;
    }


}