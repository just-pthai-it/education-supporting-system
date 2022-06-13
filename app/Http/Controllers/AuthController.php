<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use App\Http\Requests\LoginRequest;
use App\Services\Contracts\AuthServiceContract;

class AuthController extends Controller
{
    private AuthServiceContract $authService;

    /**
     * @param AuthServiceContract $authService
     */
    public function __construct (AuthServiceContract $authService)
    {
        $this->authService = $authService;
    }

    public function login (LoginRequest $request)
    {
        return $this->authService->login($request->username, $request->password);
    }

    public function logout ()
    {
        $this->authService->logout();
    }
}
