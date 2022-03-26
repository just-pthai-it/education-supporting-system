<?php

namespace App\Services\Contracts;

interface AccountServiceContract
{
    public function changePassword ($input);

    public function update ($uuidAccount, $values);
}
