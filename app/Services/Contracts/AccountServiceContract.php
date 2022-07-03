<?php

namespace App\Services\Contracts;

interface AccountServiceContract
{
    public function changePassword (string $uuidAccount, int $idAccount, array $inputs);

    public function resetPassword (string $email);

    public function confirmResetPassword (string $newPassword);

    public function update ($uuidAccount, $values);
}
