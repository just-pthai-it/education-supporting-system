<?php

namespace App\Services\Contracts;

interface AccountServiceContract
{
    public function changePassword (string $uuidAccount, int $idAccount, array $inputs);

    public function requestResetPassword (string $email);

    public function verifyResetPassword (array $inputs);

    public function resetPassword (array $inputs);

    public function update ($uuidAccount, $values);
}
