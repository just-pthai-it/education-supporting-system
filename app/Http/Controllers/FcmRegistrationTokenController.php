<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Contracts\FcmRegistrationTokenServiceContract;

class FcmRegistrationTokenController extends Controller
{
    private FcmRegistrationTokenServiceContract $FCMRegistrationTokenService;

    /**
     * @param FcmRegistrationTokenServiceContract $FCMRegistrationTokenService
     */
    public function __construct (FcmRegistrationTokenServiceContract $FCMRegistrationTokenService)
    {
        $this->FCMRegistrationTokenService = $FCMRegistrationTokenService;
    }

    public function create(Request $request)
    {

    }
}
