<?php

namespace App\Services\Contracts;

interface ClassServiceContract
{
    public function getClassesByIdAcademicYearsAndIdFaculties ($id_academic_years, $id_faculties);
}
