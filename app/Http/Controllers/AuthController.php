<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Resources\LoginResponse;
use App\Exceptions\InvalidFormRequestException;
use App\Http\FormRequest\LoginForm;
use App\Services\Contracts\AuthServiceContract;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected LoginForm $loginForm;
    private AuthServiceContract $authService;

    /**
     * @param LoginForm           $loginForm
     * @param AuthServiceContract $authService
     */
    public function __construct (LoginForm $loginForm, AuthServiceContract $authService)
    {
        $this->loginForm   = $loginForm;
        $this->authService = $authService;
    }

    /**
     * @throws InvalidFormRequestException
     */
    public function login (Request $request) : JsonResponse
    {
        $this->loginForm->validate($request);
        $data = $this->authService->login($request->username, $request->password);
        return (new LoginResponse($data['response']))
            ->response()->header('Authorization', "Bearer {$data['token']}");
    }

    public function logout ()
    {
        $this->authService->logout();
    }
}
