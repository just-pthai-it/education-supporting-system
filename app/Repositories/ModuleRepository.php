<?php

namespace App\Repositories;

use App\Models\Module;
use App\Repositories\Contracts\ModuleRepositoryContract;

class ModuleRepository implements ModuleRepositoryContract
{
    public function getAll () : array
    {
        return Module::select('id as id_module', 'module_name')
                     ->get()
                     ->toArray();
    }
}
