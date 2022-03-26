<?php

namespace App\Repositories\Contracts;

interface ModuleClassRepositoryContract extends BaseRepositoryContract
{
    public function findByIdDepartment ($id_department, $id_study_sessions);

    public function getIDModuleClassesMissing ($id_module_classes);

    public function softDeleteByIdDepartmentAndIdStudySession (string $idDepartment, int $idStudySession);
}
