<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserData;
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

    public function login (LoginRequest $request) : JsonResponse
    {
        $data = $this->authService->login($request->username, $request->password);
        return (new UserData($data['response']))
            ->response()->header('Authorization', "Bearer {$data['token']}");
    }

    public function logout ()
    {
        $this->authService->logout();
    }
}
