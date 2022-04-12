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

    public function readMany (array $inputs)
    {
        return $this->classDepository->find(['id', 'id_academic_year', 'id_faculty'],
                                            [], [], [], [['filter', $inputs]]);
    }
}
