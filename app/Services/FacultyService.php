<?php

namespace App\Services;

use App\Repositories\Contracts\FacultyRepositoryContract;

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

    public function getAll (array $inputs)
    {
        return $this->facultyDepository->find(['id', 'name'], [], [], [],
                                              [['with', 'departments:id,name,id_faculty'],
                                               ['filter', $inputs]]);
    }
}