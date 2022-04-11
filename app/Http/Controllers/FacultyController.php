<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\FacultyResource;
use App\Services\Contracts\FacultyServiceContract;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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

    public function readMany (Request $request) : AnonymousResourceCollection
    {
        $faculties = $this->facultyService->readMany($request->all());
        return FacultyResource::collection($faculties);
    }
}
