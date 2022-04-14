<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\ExamScheduleResource;
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

    public function getExamSchedules (Request $request,
                                      string  $idDepartment) : AnonymousResourceCollection
    {
        Gate::authorize('get-department-exam-schedule');
        $exam_schedules = $this->departmentService->getExamSchedules($idDepartment,
                                                                     $request->all());
        return ExamScheduleResource::collection($exam_schedules);
    }

    public function getTeachers (Request $request, string $idDepartment)
    {
        $data = $this->departmentService->getTeachers($idDepartment, $request->all());
        return response(['data' => $data]);
    }
}
