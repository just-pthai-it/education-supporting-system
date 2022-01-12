<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\FacultyResource;
use App\Http\Resources\FixedScheduleResource;
use App\Services\Contracts\DepartmentServiceContract;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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

    public function getAllDepartments ()
    {
        return response(FacultyResource::collection($this->departmentService->getAllDepartments()));
    }

    public function getFixedSchedulesByStatus (Request $request,
                                                       $id_department) : AnonymousResourceCollection
    {
        $fixed_schedules = $this->departmentService->getFixedSchedulesByStatus($id_department,
                                                                               $request->status);
        return FixedScheduleResource::collection($fixed_schedules);
    }
}
