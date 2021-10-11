<?php

namespace App\Http\Controllers;

use App\Services\Contracts\FacultyClassServiceContract;
use Illuminate\Http\Request;

class FacultyClassController extends Controller
{
    private FacultyClassServiceContract $facultyClassService;

    /**
     * @param FacultyClassServiceContract $facultyClassService
     */
    public function __construct (FacultyClassServiceContract $facultyClassService)
    {
        $this->facultyClassService = $facultyClassService;
    }

    public function getFacultyClasses (Request $request)
    {
        $data = $this->facultyClassService->getFacultyClasses($request->academic_year, $request->faculty);
        return response($data)->header('Content-Type', 'application/data');
    }
}
