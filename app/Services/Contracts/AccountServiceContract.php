<?php

namespace App\Services\Contracts;

interface AccountServiceContract
{
    public function changePassword ($username, $password, $new_password);
}
