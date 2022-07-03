<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AccountPatchRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ResetPasswordPostRequest;
use App\Http\Requests\ConfirmResetPasswordPatchRequest;
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

    public function resetPassword (ResetPasswordPostRequest $request)
    {
        $this->accountService->resetPassword($request->validated()['email']);
    }

    public function confirmResetPassword (ConfirmResetPasswordPatchRequest $request)
    {
        $this->accountService->confirmResetPassword($request->validated()['new_password']);
    }

    public function update (AccountPatchRequest $request, $uuidAccount)
    {
        $this->accountService->update($uuidAccount, $request->validated());
    }
}
