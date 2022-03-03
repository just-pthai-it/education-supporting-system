<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserData;
use App\Services\Contracts\AuthServiceContract;

class UserController extends Controller
{
    private AuthServiceContract $authService;

    /**
     * @param AuthServiceContract $authService
     */
    public function __construct (AuthServiceContract $authService)
    {
        $this->authService = $authService;
    }

    public function getUserInfo () : UserData
    {
        $data = $this->authService->getUserInfo();
        return new UserData($data);
    }
}
