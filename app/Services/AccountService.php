<?php

namespace App\Services;

use App\Models\Account;
use App\Helpers\GFunction;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMailNotify;
use App\Exceptions\CustomBadHttpRequestException;
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
    public function changePassword (string $uuidAccount, int $idAccount, array $inputs) : void
    {
        $credentials = [
            'id'       => $idAccount,
            'uuid'     => GFunction::uuidToBin($uuidAccount),
            'password' => $inputs['password']
        ];

        $this->__verifyCredentials($credentials);
        $this->accountDepository->updateByIds($idAccount,
                                              ['password' => bcrypt($inputs['new_password'])]);
    }

    /**
     * @param array $credentials *
     *
     * @throws CustomAuthenticationException
     */
    private function __verifyCredentials (array $credentials) : void
    {
        if (auth()->attempt($credentials) === false)
        {
            $messages = json_encode(['Invalid username, email or password']);
            throw new CustomAuthenticationException($messages, 419);
        }
    }

    /**
     * @throws CustomBadHttpRequestException
     */
    public function resetPassword (string $email)
    {
        $account = $this->__readAccountByEmail($email);
        if (is_null($account))
        {
            $messages = json_encode(['Email is not available']);
            throw new CustomBadHttpRequestException($messages, 400);
        }

        $token        = auth()->fromUser($account);
        $frontEndHost = config('app.front_end_host');
        $this->__sendConfirmResetPasswordMail($account, $token, $frontEndHost);
    }

    private function __readAccountByEmail (string $email) : ?Account
    {
        return $this->accountDepository->find(['*'], [['email', '=', $email]])[0] ?? null;
    }

    private function __sendConfirmResetPasswordMail (Account $account, string $token,
                                                     string  $frontEndHost)
    {
        Mail::queue(new ResetPasswordMailNotify($account, $token, $frontEndHost));
    }

    public function confirmResetPassword (string $newPassword)
    {
        auth()->user()->update(['password' => bcrypt($newPassword)]);
        auth()->logout();
    }

    public function update ($uuidAccount, $values) : void
    {
        $this->accountDepository->update($values,
                                         [['uuid', '=', GFunction::uuidToBin($uuidAccount)]]);
    }
}
