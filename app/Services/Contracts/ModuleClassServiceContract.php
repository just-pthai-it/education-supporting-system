<?php

namespace App\Services\Contracts;

interface ModuleClassServiceContract
{
    public function updateModuleClass ($module_class);

    public function getRecentModuleClasses ($id_teacher);
}
