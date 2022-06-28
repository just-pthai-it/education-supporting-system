<?php

namespace App\Services;

use App\Http\Resources\FacultyResource;
use App\Repositories\Contracts\FacultyRepositoryContract;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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

    public function readMany (array $inputs) : AnonymousResourceCollection
    {
        $faculties = $this->facultyDepository->find(['id', 'name'], [], [], [],
                                                    [['filter', $inputs]]);

        if (request()->route('additional') == 'with-departments')
        {
            $faculties->load(['departments:id,name,id_faculty']);
        }

        return FacultyResource::collection($faculties);
    }
}