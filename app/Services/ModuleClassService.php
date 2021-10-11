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

    public function getRecentModuleClasses ()
    {
        return $this->moduleClassDepository->getModuleClasses1();
    }
}
