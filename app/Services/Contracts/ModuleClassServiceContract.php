<?php

namespace App\Services\Contracts;

interface ModuleClassServiceContract
{
    public function updateModuleClass ($ids, $id_teacher);

    public function getModuleClassesByIdTeacher ($id_teacher, $term, $study_sessions);

    public function getModuleClassesByIdDepartment ($id_department, $term, $study_sessions);
}
