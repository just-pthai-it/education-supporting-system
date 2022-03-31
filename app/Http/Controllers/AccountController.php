<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountPatchRequest;
use App\Http\Resources\NotificationResource;
use App\Exceptions\InvalidFormRequestException;
use App\Http\FormRequest\ChangePasswordForm;
use App\Services\Contracts\AccountServiceContract;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AccountController extends Controller
{
    private ChangePasswordForm $form;
    private AccountServiceContract $accountService;

    /**
     * @param ChangePasswordForm     $form
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
        $this->accountService->changePassword($request->only('password', 'new_password'));
    }

    public function update (AccountPatchRequest $request, $uuidAccount)
    {
        $this->accountService->update($uuidAccount, $request->validated());
    }

    public function readManyNotifications (Request $request,
                                                   $uuidAccount) : AnonymousResourceCollection
    {
        $data = $this->accountService->readManyNotifications($uuidAccount, $request->all());
        return NotificationResource::collection($data);
    }
}
