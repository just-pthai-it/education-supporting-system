<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ScheduleResource;
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

    public function getSchedules (Request $request, $id_department)
    {
        $schedules = $this->departmentService->getSchedules($id_department,
                                                            $request->start,
                                                            $request->end);

        return ScheduleResource::collection($schedules)->all();
    }

    public function getExamSchedules (Request $request, $id_department)
    {
        $exam_schedules = $this->departmentService->getExamSchedules($id_department,
                                                                     $request->start,
                                                                     $request->end);

        return ExamScheduleResource::collection($exam_schedules)->all();
    }

    public function getFixedSchedulesByStatus (Request $request,
                                                       $id_department) : AnonymousResourceCollection
    {
        $fixed_schedules = $this->departmentService->getFixedSchedulesByStatus($id_department,
                                                                               $request->status);
        return FixedScheduleResource::collection($fixed_schedules);
    }


    public function getModuleClassesByStudySessions (Request $request, $id_department)
    {
        return response($this->departmentService->getModuleClassesByStudySessions($id_department,
                                                                                  $request->term,
                                                                                  $request->ss));
    }
}
