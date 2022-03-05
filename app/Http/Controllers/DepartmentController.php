<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\ScheduleResource;
use App\Http\Resources\ModuleClassResource;
use App\Http\Resources\ExamScheduleResource;
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

    public function getSchedules (Request $request,
                                          $id_department) : AnonymousResourceCollection
    {
        Gate::authorize('get-department-schedule');
        $schedules = $this->departmentService->getSchedules($id_department, $request->all());
        return ScheduleResource::collection($schedules);
    }

    public function getExamSchedules (Request $request,
                                              $id_department) : AnonymousResourceCollection
    {
        Gate::authorize('get-department-exam-schedule');
        $exam_schedules = $this->departmentService->getExamSchedules($id_department,
                                                                     $request->all());
        return ExamScheduleResource::collection($exam_schedules);
    }

    public function getFixedSchedules (Request $request,
                                               $id_department) : AnonymousResourceCollection
    {
        Gate::authorize('get-department-fixed-schedule', [$request->all()]);
        $fixed_schedules = $this->departmentService->getFixedSchedules($id_department,
                                                                       $request->all());
        return FixedScheduleResource::collection($fixed_schedules);
    }


    public function getModuleClasses (Request $request,
                                              $id_department) : AnonymousResourceCollection
    {
        $response = $this->departmentService->getModuleClasses($id_department, $request->all());
        return ModuleClassResource::collection($response);
    }

    public function getTeachers (Request $request, $id_department)
    {
        $data = $this->departmentService->getTeachers($id_department, $request->all());
        return response(['data' => $data]);
    }
}
