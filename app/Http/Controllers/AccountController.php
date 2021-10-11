<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidFormRequestException;
use App\Http\FormRequest\ChangePasswordForm;
use App\Services\Contracts\AccountServiceContract;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    private ChangePasswordForm $form;
    private AccountServiceContract $accountService;

    /**
     * @param ChangePasswordForm $form
     * @param AccountServiceContract $accountService
     */
    public function __construct (ChangePasswordForm $form, AccountServiceContract $accountService)
    {
        $this->form           = $form;
        $this->accountService = $accountService;
    }

    /**
     * @throws InvalidFormRequestException
     */
    public function changePassword (Request $request)
    {
        $this->form->validate($request);
        $this->accountService->changePassword($request->username, $request->password, $request->new_password);
    }
}
