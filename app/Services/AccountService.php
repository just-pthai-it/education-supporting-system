<?php

namespace App\Services;

use App\Helpers\GFunction;
use App\Repositories\Contracts\AccountRepositoryContract;
use App\Exceptions\CustomAuthenticationException;

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
     * @param string $uuidAccount
     * @param int    $idAccount
     * @param array  $inputs
     *
     * @throws CustomAuthenticationException
     */
    public function changePassword (string $uuidAccount, int $idAccount, array $inputs)
    {
        $this->_verifyCredentials($uuidAccount, $idAccount, $inputs['password']);
        $this->accountDepository->updateByIds($idAccount,
                                              ['password' => bcrypt($inputs['new_password'])]);
    }

    /**
     * @param string $uuidAccount
     * @param int    $idAccount
     * @param string $password
     *
     * @throws CustomAuthenticationException
     */
    private function _verifyCredentials (string $uuidAccount, int $idAccount, string $password)
    {
        $credentials = [
            'id'       => $idAccount,
            'uuid'     => GFunction::uuidToBin($uuidAccount),
            'password' => $password
        ];

        if (auth()->attempt($credentials) === false)
        {
            $messages = json_encode(['Invalid username, email or password']);
            throw new CustomAuthenticationException($messages, 419);
        }
    }

    public function update ($uuidAccount, $values)
    {
        $this->accountDepository->update($values,
                                         [['uuid', '=', GFunction::uuidToBin($uuidAccount)]]);
    }
}
