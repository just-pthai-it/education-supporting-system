<?php

namespace App\Repositories\Contracts;

interface ModuleClassRepositoryContract extends BaseRepositoryContract
{
    public function findByIdDepartment ($idDepartment, array $inputs);

    public function getIDModuleClassesMissing ($id_module_classes);

    public function softDeleteByIdDepartmentAndIdStudySession (string $idDepartment,
                                                               int    $idStudySession);
}
