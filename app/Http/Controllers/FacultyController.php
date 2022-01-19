<?php

namespace App\Http\Controllers;

use App\Http\Resources\FacultyResource;
use App\Services\Contracts\FacultyServiceContract;

class FacultyController extends Controller
{
    private FacultyServiceContract $facultyService;

    /**
     * @param FacultyServiceContract $facultyService
     */
    public function __construct (FacultyServiceContract $facultyService)
    {
        $this->facultyService = $facultyService;
    }

    public function getAllWithDepartments ()
    {
        return FacultyResource::collection($this->facultyService->getAllWithDepartments())->all();
    }

    public function getIDFaculties ()
    {
        return $this->facultyService->getIdFaculties();
    }
}
