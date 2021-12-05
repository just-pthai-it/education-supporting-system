<?php

namespace App\Http\Controllers;

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
    public function login (Request $request)
    {
        $this->loginForm->validate($request);
        $arr = $this->authService->login($request->username, $request->password);

        return response(json_encode($arr['data']))
            ->header('Authorization', 'Bearer ' . $arr['token']);
    }

    public function logout ()
    {
        $this->authService->logout();
        return response();
    }
}
