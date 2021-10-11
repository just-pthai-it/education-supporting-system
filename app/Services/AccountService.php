<?php

namespace App\Services;

use App\Repositories\Contracts\AccountRepositoryContract;
use App\Exceptions\InvalidAccountException;
use App\Services\Contracts\AccountServiceContract;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class AccountService implements AccountServiceContract
{
    private AccountRepositoryContract $accountDepository;

    /**
     * @param AccountRepositoryContract $accountDepository
     */
    public function __construct (AccountRepositoryContract $accountDepository)
    {
        $this->accountDepository = $accountDepository;
    }

    /**
     * @throws InvalidAccountException
     */
    public function changePassword ($username, $password, $new_password)
    {
        $this->_verifyAccount($username, $password);
        $this->_updatePassword($username, $new_password);
    }

    /**
     * @throws InvalidAccountException
     */
    private function _verifyAccount ($username, $password)
    {
        $credential = [
            'username' => $username,
            'password' => $password
        ];

        if (!auth()->attempt($credential))
        {
            throw new InvalidAccountException();
        }
    }

    private function _updatePassword ($username, $password)
    {
        $this->accountDepository->updatePassword($username, bcrypt($password));
    }
}
