<?php

namespace App\Repositories\Contracts;

interface ModuleRepositoryContract extends BaseRepositoryContract
{
    public function getIDModulesMissing ($id_modules);
}
