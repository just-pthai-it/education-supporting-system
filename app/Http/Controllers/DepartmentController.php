<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    public function getTeachers (Request $request, string $idDepartment)
    {
        $data = $this->departmentService->getTeachers($idDepartment, $request->all());
        return response(['data' => $data]);
    }
}
