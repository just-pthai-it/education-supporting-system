<?php

namespace App\Http\Controllers;

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

    public function getIDFaculties ()
    {
        $data = $this->facultyService->getIDFaculties();
        return response($data)->header('Content-Type', 'application/data');
    }
}
