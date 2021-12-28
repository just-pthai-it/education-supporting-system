<?php

namespace App\Http\Controllers;

use App\Http\Resources\FacultyResource;
use App\Services\Contracts\DepartmentServiceContract;

class DepartmentController extends Controller
{
    private DepartmentServiceContract $departmentService;

    /**
     * @param DepartmentServiceContract $departmentService
     */
    public function __construct (DepartmentServiceContract $departmentService)
    {
        $this->departmentService = $departmentService;
    }

    public function getAllDepartments()
    {
        return response(FacultyResource::collection($this->departmentService->getAllDepartments()));
    }
}
