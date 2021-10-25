<?php

namespace App\Repositories\Contracts;

interface ModuleRepositoryContract
{
    public function getAll ();

    public function getIDModulesMissing ($id_modules);
}
