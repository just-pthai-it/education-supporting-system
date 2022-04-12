<?php

namespace App\Http\Controllers;

use App\Services\Contracts\ClassServiceContract;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    private ClassServiceContract $facultyClassService;

    /**
     * @param ClassServiceContract $facultyClassService
     */
    public function __construct (ClassServiceContract $facultyClassService)
    {
        $this->facultyClassService = $facultyClassService;
    }

    public function readMany (Request $request)
    {
        return $this->facultyClassService->readMany($request->all());
    }
}
