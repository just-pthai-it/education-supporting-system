<?php

namespace App\Services;

use App\Repositories\Contracts\FacultyRepositoryContract;
use App\Helpers\GData;

class FacultyService implements Contracts\FacultyServiceContract
{
    private FacultyRepositoryContract $facultyDepository;

    /**
     * @param FacultyRepositoryContract $facultyDepository
     */
    public function __construct (FacultyRepositoryContract $facultyDepository)
    {
        $this->facultyDepository = $facultyDepository;
    }

    public function getAllWithDepartments ()
    {
        return $this->facultyDepository->findAllWithDepartments();
    }

    public function getIdFaculties ()
    {
        return $this->facultyDepository->find(['id', 'name'],
                                              [['id', 'not in', GData::$id_faculties_not_query]],
                                              [['id']]);
    }
}