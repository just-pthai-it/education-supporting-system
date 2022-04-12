<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountPatchRequest;
use App\Http\Requests\ChangePasswordRequest;
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

    public function update (AccountPatchRequest $request, $uuidAccount)
    {
        $this->accountService->update($uuidAccount, $request->validated());
    }
}
