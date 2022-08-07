<?php

namespace App\Repositories\Contracts;

interface ClassRepositoryContract extends BaseRepositoryContract
{
    public function findIdClassesByIdAcademicYearAndIdFacultyPairs (array $idAcademicYears,
                                                                    array $idFaculties);
}
