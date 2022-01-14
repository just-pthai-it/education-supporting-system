<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\FacultyResource;
use App\Http\Resources\ScheduleResource;
use App\Http\Resources\FixedScheduleResource;
use App\Services\Contracts\ScheduleServiceContract;
use App\Services\Contracts\DepartmentServiceContract;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DepartmentController extends Controller
{
    private DepartmentServiceContract $departmentService;
    private ScheduleServiceContract $scheduleService;

    /**
     * @param DepartmentServiceContract $departmentService
     * @param ScheduleServiceContract   $scheduleService
     */
    public function __construct (DepartmentServiceContract $departmentService,
                                 ScheduleServiceContract   $scheduleService)
    {
        $this->departmentService = $departmentService;
        $this->scheduleService   = $scheduleService;
    }

    public function getAllDepartments ()
    {
        return response(FacultyResource::collection($this->departmentService->getAllDepartments()));
    }

    public function getSchedules (Request $request, $id_department)
    {
        $schedules = $this->scheduleService->getDepartmentSchedules($id_department,
                                                                    $request->start,
                                                                    $request->end);
        return ScheduleResource::collection($schedules)->all();
    }

    public function getFixedSchedulesByStatus (Request $request,
                                                       $id_department) : AnonymousResourceCollection
    {
        $fixed_schedules = $this->departmentService->getFixedSchedulesByStatus($id_department,
                                                                               $request->status);
        return FixedScheduleResource::collection($fixed_schedules);
    }
}
