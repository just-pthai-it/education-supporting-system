<?php

namespace App\Services\Contracts;

interface RegisterServiceContract
{
    public function process1 ($id_student, $qldt_password);

    public function process2 ($data);
}
