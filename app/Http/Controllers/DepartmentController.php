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
                                  string  $idDepartment) : AnonymousResourceCollection
    {
        Gate::authorize('get-department-schedule');
        $schedules = $this->departmentService->getSchedules($idDepartment, $request->all());
        return ScheduleResource::collection($schedules);
    }

    public function getExamSchedules (Request $request,
                                      string  $idDepartment) : AnonymousResourceCollection
    {
        Gate::authorize('get-department-exam-schedule');
        $exam_schedules = $this->departmentService->getExamSchedules($idDepartment,
                                                                     $request->all());
        return ExamScheduleResource::collection($exam_schedules);
    }

    public function getFixedSchedules (Request $request,
                                       string  $idDepartment) : AnonymousResourceCollection
    {
        Gate::authorize('get-department-fixed-schedule', [$request->all()]);
        $fixed_schedules = $this->departmentService->getFixedSchedules($idDepartment,
                                                                       $request->all());
        return FixedScheduleResource::collection($fixed_schedules);
    }

    public function getTeachers (Request $request, string $idDepartment)
    {
        $data = $this->departmentService->getTeachers($idDepartment, $request->all());
        return response(['data' => $data]);
    }
}
