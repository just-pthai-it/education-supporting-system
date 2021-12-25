<?php

namespace App\Repositories\Contracts;

interface ModuleRepositoryContract
{
    public function upsertMultiple ($modules);

    public function getIDModulesMissing ($id_modules);
}
