<?php

namespace App\Services;

use App\Repositories\Contracts\ClassRepositoryContract;
use App\Services\Contracts\ClassServiceContract;

class ClassService implements ClassServiceContract
{
    private ClassRepositoryContract $classDepository;

    /**
     * FacultyClassService constructor.
     * @param ClassRepositoryContract $classDepository
     */
    public function __construct (ClassRepositoryContract $classDepository)
    {
        $this->classDepository = $classDepository;
    }

    public function getFacultyClasses ($id_academic_years, $id_faculties)
    {
        $id_academic_years = explode(',', $id_academic_years);
        $id_faculties      = explode(',', $id_faculties);

        return $this->classDepository->getClasses($id_academic_years, $id_faculties);
    }
}
