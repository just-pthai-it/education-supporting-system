<?php

namespace App\Services;

use App\Helpers\GFunction;
use App\Repositories\Contracts\AccountRepositoryContract;
use App\Exceptions\InvalidAccountException;
use App\Repositories\Contracts\NotificationRepositoryContract;

class AccountService implements Contracts\AccountServiceContract
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
     * @param $input *
     *
     * @throws InvalidAccountException
     */
    public function changePassword ($input)
    {
        $this->_verifyAccount(auth()->user()->id, $input['password']);
        $this->_updatePassword(auth()->user()->id, $input['new_password']);
    }

    /**
     * @throws InvalidAccountException
     */
    private function _verifyAccount ($id_account, $password)
    {
        $credential = [
            'id'       => $id_account,
            'password' => $password
        ];

        if (!auth()->attempt($credential))
        {
            throw new InvalidAccountException();
        }
    }

    private function _updatePassword ($id_account, $password)
    {
        $this->accountDepository->updatePassword($id_account, bcrypt($password));
    }

    public function update ($uuidAccount, $values)
    {
        $this->accountDepository->update($values,
                                         [['uuid', '=', GFunction::uuidToBin($uuidAccount)]]);
    }

    public function readManyNotifications (string $uuidAccount, array $inputs)
    {
        return $this->accountDepository->findNotifications($uuidAccount, $inputs)[0]->notifications;
    }
}
