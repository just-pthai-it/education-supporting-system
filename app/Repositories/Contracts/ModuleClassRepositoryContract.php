<?php

namespace App\Repositories\Contracts;

interface ModuleClassRepositoryContract
{
    public function getModuleClasses1 ($id_teacher);

    public function getModuleClasses2 ($module_class_list);
}
