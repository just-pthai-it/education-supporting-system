<?php

namespace App\Services;

use App\Repositories\Contracts\ModuleClassRepositoryContract;

class ModuleClassService implements Contracts\ModuleClassServiceContract
{
    private ModuleClassRepositoryContract $moduleClassDepository;

    /**
     * @param ModuleClassRepositoryContract $moduleClassDepository
     */
    public function __construct (ModuleClassRepositoryContract $moduleClassDepository)
    {
        $this->moduleClassDepository = $moduleClassDepository;
    }
    public function updateModuleClass ($module_class)
    {
        $this->moduleClassDepository->update($module_class);
    }

    public function getRecentModuleClasses ($id_teacher)
    {
        return $this->moduleClassDepository->getModuleClasses($id_teacher);
    }
}
