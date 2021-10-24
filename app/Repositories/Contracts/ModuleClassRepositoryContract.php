<?php

namespace App\Repositories\Contracts;

interface ModuleClassRepositoryContract
{
    public function insert ($data);

    public function insertPivotMultiple ($id_module_class, $id_students);

    public function getModuleClasses ($id_teacher);

    public function getIDModuleClassesNotInDatabase ($id_module_classes);
}
