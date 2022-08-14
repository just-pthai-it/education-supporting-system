<?php

namespace App\Services;

use App\Models\Account;
use App\Helpers\GFunction;
use Illuminate\Support\Str;
use App\Models\PasswordReset;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMailNotify;
use App\Exceptions\CustomHttpException;
use App\Repositories\Contracts\AccountRepositoryContract;
use App\Repositories\Contracts\PasswordResetRepositoryContract;

class AccountService implements Contracts\AccountServiceContract
{
    private AccountRepositoryContract $accountDepository;
    private PasswordResetRepositoryContract $passwordResetRepository;

    private const MAXIMUM_MINUTES_TOKEN_LAST = 15;
    private const SECOND_DIFFERENT = 30;

    /**
     * @param AccountRepositoryContract       $accountDepository
     * @param PasswordResetRepositoryContract $passwordResetRepository
     */
    public function __construct (AccountRepositoryContract       $accountDepository,
                                 PasswordResetRepositoryContract $passwordResetRepository)
    {
        $this->accountDepository       = $accountDepository;
        $this->passwordResetRepository = $passwordResetRepository;
    }

    /**
     * @param string $uuidAccount
     * @param int    $idAccount
     * @param array  $inputs
     *
     * @throws CustomHttpException
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
     * @throws CustomHttpException
     */
    private function __verifyCredentials (array $credentials) : void
    {
        if (auth()->attempt($credentials) === false)
        {
            $messages = json_encode(['Invalid username, email or password']);
            throw new CustomHttpException($messages, 419);
        }
    }

    /**
     * @throws CustomHttpException
     */
    public function requestResetPassword (string $email)
    {
        $account = $this->__readAccountByEmail($email);
        if (is_null($account))
        {
            $messages = json_encode('Email is not available');
            throw new CustomHttpException($messages, 400);
        }

        $token       = $this->__getToken();
        $frontEndUrl = config('app.front_end_url');
        $this->__createPasswordReset($account->email, $token);
        $this->__sendConfirmResetPasswordMail($account, $token, $frontEndUrl);
    }

    private function __readAccountByEmail (string $email) : ?Account
    {
        return $this->accountDepository->find(['*'], [['email', '=', $email]])[0] ?? null;
    }

    private function __getToken () : string
    {
        $hash = hash_pbkdf2("sha256", Str::random(10), config('jwt.secret'), 1000, 24, true);
        return strtoupper(bin2hex($hash));
    }

    private function __createPasswordReset (string $email, string $token)
    {
        $now       = (new Carbon(null, '+7'));
        $createdAt = $now->format('Y-m-d H:i:s');
        $expiredAt = $now->addMinutes(self::MAXIMUM_MINUTES_TOKEN_LAST)
                         ->format('Y-m-d H:i:s');

        $this->passwordResetRepository->upsert(['email'      => $email,
                                                'token'      => $token,
                                                'created_at' => $createdAt,
                                                'expired_at' => $expiredAt],
                                               [],
                                               ['token'      => $token,
                                                'created_at' => $createdAt,
                                                'expired_at' => $expiredAt]);
    }

    private function __sendConfirmResetPasswordMail (Account $account, string $token,
                                                     string  $frontEndHost)
    {
        Mail::queue(new ResetPasswordMailNotify($account, $token, $frontEndHost));
    }

    /**
     * @throws CustomHttpException
     */
    public function verifyResetPassword (array $inputs)
    {
        $this->__verifyPasswordReset($inputs['email'], $inputs['token']);
    }

    /**
     * @throws CustomHttpException
     */
    private function __verifyPasswordReset (string $email, string $token)
    {
        $passwordReset = $this->__readPasswordResetByEmailAndToken($email, $token);

        if (is_null($passwordReset))
        {
            throw new CustomHttpException('Email or token is not available', 400);
        }

        if ($this->__isTokenExpired($passwordReset))
        {
            throw new CustomHttpException('Token is expired', 400);
        }
    }

    /**
     * @throws CustomHttpException
     */
    public function resetPassword (array $inputs) : void
    {
        $passwordReset = $this->__readPasswordResetByEmailAndToken($inputs['email'],
                                                                   $inputs['token']);

        $this->__verifyPasswordReset($inputs['email'], $inputs['token']);

        $account = $this->__readAccountByEmail($inputs['email']);
        $account->update(['password' => bcrypt($inputs['new_password'])]);
        $passwordReset->delete();
    }

    private function __readPasswordResetByEmailAndToken (string $email,
                                                         string $token) : ?PasswordReset
    {
        $conditions = [['email', '=', $email], ['token', '=', $token]];
        return $this->passwordResetRepository->find(['*'], $conditions)[0] ?? null;
    }

    private function __isTokenExpired (PasswordReset $passwordReset) : bool
    {
        $now       = (new Carbon(null))->format('U.u');
        $expiredAt = Carbon::createFromFormat('Y-m-d H:i:s', $passwordReset->expired_at, '+7')
                           ->format('U.u');

        return $expiredAt - $now - self::SECOND_DIFFERENT < 0;
    }

    public function update ($uuidAccount, $values) : void
    {
        $this->accountDepository->update($values,
                                         [['uuid', '=', GFunction::uuidToBin($uuidAccount)]]);
    }
}
