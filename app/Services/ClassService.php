<?php

namespace App\Services;

use App\Repositories\Contracts\ClassRepositoryContract;

class ClassService implements Contracts\ClassServiceContract
{
    private ClassRepositoryContract $classDepository;

    /**
     * @param ClassRepositoryContract $classDepository
     */
    public function __construct (ClassRepositoryContract $classDepository)
    {
        $this->classDepository = $classDepository;
    }

    public function getClassesByIdAcademicYearsAndIdFaculties ($id_academic_years, $id_faculties)
    {
        $id_academic_years = explode(',', $id_academic_years);
        $id_faculties      = explode(',', $id_faculties);

        return $this->classDepository->find(['id_academic_year', 'id_faculty', 'id as id_class'],
                                            [['id_academic_year', 'in', $id_academic_years],
                                             ['id_faculty', 'in', $id_faculties]],
                                            [['id_academic_year'], ['id_faculty'], ['id']]);
    }
}
