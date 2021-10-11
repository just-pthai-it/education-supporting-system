<?php

namespace App\Services\Contracts;

interface LoginServiceContract
{
    public function login ($username, $password);
}
