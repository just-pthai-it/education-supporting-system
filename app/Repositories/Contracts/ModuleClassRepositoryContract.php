<?php

namespace App\Repositories\Contracts;

interface ModuleClassRepositoryContract
{
    public function insertMultiple ($data);

    public function insertPivotMultiple ($id_module_class, $id_students);

    public function update ($new_module_class);

    public function getModuleClasses ($id_teacher);

    public function getIDModuleClassesMissing ($id_module_classes);
}
