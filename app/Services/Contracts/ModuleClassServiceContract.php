<?php

namespace App\Services\Contracts;

interface ModuleClassServiceContract
{
    public function readMany (array $inputs);

    public function readManyByIdDepartment (string $idDepartment, array $inputs);

    public function updateMany (array $inputs);

    public function destroyMany (array $inputs);
}
