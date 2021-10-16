<?php

namespace App\Services\Contracts;

interface AuthServiceContract
{
    public function login ($username, $password);

    public function logout ();
}