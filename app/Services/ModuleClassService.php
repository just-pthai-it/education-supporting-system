<?php

namespace App\Services;

use App\Repositories\Contracts\ModuleClassRepositoryContract;
use App\Services\Contracts\ModuleClassServiceContract;

class ModuleClassService implements ModuleClassServiceContract
{
    private ModuleClassRepositoryContract $moduleClassDepository;

    /**
     * ModuleClassService constructor.
     * @param ModuleClassRepositoryContract $moduleClassDepository
     */
    public function __construct (ModuleClassRepositoryContract $moduleClassDepository)
    {
        $this->moduleClassDepository = $moduleClassDepository;
    }

    public function getRecentModuleClasses ($id_teacher)
    {
        return $this->moduleClassDepository->getModuleClasses1($id_teacher);
    }
}
