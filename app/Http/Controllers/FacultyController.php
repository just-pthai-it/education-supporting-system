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

    public function getAll (Request $request) : AnonymousResourceCollection
    {
        return FacultyResource::collection($this->facultyService->getAll($request->all()));
    }
}
