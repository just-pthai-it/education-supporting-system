<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\InvalidFormRequestException;
use App\Http\Controllers\Controller;
use App\Http\FormRequest\LoginForm;
use App\Services\Contracts\LoginServiceContract;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LoginController extends Controller
{
    protected LoginForm $loginForm;
    private LoginServiceContract $loginAppService;

    /**
     * LoginController constructor.
     * @param LoginForm $loginForm
     * @param LoginServiceContract $loginAppService
     */
    public function __construct (LoginForm $loginForm, LoginServiceContract $loginAppService)
    {
        $this->loginForm       = $loginForm;
        $this->loginAppService = $loginAppService;
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|Response
     * @throws InvalidFormRequestException
     */
    public function login (Request $request)
    {
        $this->loginForm->validate($request);
        $arr = $this->loginAppService->login($request->username, $request->password);

        return response(json_encode($arr['data']))
            ->header('Content-Type', 'application/json')
            ->header('Authorization', 'Bearer ' . $arr['token']);
    }
}
