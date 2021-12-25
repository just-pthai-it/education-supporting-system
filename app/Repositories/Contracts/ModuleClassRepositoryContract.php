<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface ModuleClassRepositoryContract
{
    public function insertMultiple ($data);

    public function insertPivotMultiple ($id_module_class, $id_students);

    public function update ($new_module_class);

    public function findByIdTeacher ($id_teacher, $id_study_sessions);

    public function findByIdDepartment ($id_department, $id_study_sessions);

    public function getIDModuleClassesMissing ($id_module_classes);
}
