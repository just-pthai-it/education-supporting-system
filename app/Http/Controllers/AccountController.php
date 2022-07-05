<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AccountPatchRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\VerifyResetPasswordPostRequest;
use App\Http\Requests\RequestResetPasswordPostRequest;
use App\Http\Requests\ResetPasswordPatchRequest;
use App\Services\Contracts\AccountServiceContract;

class AccountController extends Controller
{
    private AccountServiceContract $accountService;

    /**
     * @param AccountServiceContract $accountService
     */
    public function __construct (AccountServiceContract $accountService)
    {
        $this->accountService = $accountService;
    }

    public function changePassword (ChangePasswordRequest $request, $uuidAccount)
    {
        $this->accountService->changePassword($uuidAccount, auth()->user()->id,
                                              $request->validated());
    }

    public function requestResetPassword (RequestResetPasswordPostRequest $request)
    {
        $this->accountService->requestResetPassword($request->validated()['email']);
    }

    public function verifyResetPassword (VerifyResetPasswordPostRequest $request)
    {
        $this->accountService->verifyResetPassword($request->validated());
    }

    public function resetPassword (ResetPasswordPatchRequest $request)
    {
        $this->accountService->resetPassword($request->validated());
    }

    public function update (AccountPatchRequest $request, $uuidAccount)
    {
        $this->accountService->update($uuidAccount, $request->validated());
    }
}
