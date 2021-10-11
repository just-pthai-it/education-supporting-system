<?php

namespace App\Repositories;

use App\Models\ModuleClass;
use App\Models\SchoolYear;
use App\Repositories\Contracts\ModuleClassRepositoryContract;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ModuleClassRepository implements ModuleClassRepositoryContract
{
    public function getModuleClasses1 () : Collection
    {
        return ModuleClass::where('id_school_year', '>=', SchoolYear::max('id') - 1)
                          ->orderBy('id')
                          ->select('id as id_module_class', 'module_class_name')
                          ->get();
    }

    public function getModuleClasses2 ($module_class_list) : array
    {
        return ModuleClass::whereIn('id', $module_class_list)
                          ->pluck('id')
                          ->toArray();
    }
}
