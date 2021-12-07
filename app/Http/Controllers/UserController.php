<?php

namespace App\Http\Controllers;

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

    public function getUserInfo ()
    {
        $data = $this->authService->getUserInfo();
        return response($data);
    }
}
