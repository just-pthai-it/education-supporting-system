<?php

namespace App\Repositories\Contracts;

interface ModuleClassRepositoryContract
{
    public function insertPivotMultiple ($id_module_class, $id_students);

    public function getModuleClasses1 ($id_teacher);

    public function getModuleClasses2 ($module_class_list);

    public function getIDModuleClassesNotInDatabase ($id_module_classes);
}
