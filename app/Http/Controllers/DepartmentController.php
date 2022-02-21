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

    public function getSchedulesByDate (Request $request, $id_department)
    {
        $schedules = $this->departmentService->getSchedulesByDate($id_department,
                                                                  $request->start,
                                                                  $request->end);

        return ScheduleResource::collection($schedules)->all();
    }

    public function getExamSchedulesByDate (Request $request, $id_department)
    {
        $exam_schedules = $this->departmentService->getExamSchedulesByDate($id_department,
                                                                           $request->start,
                                                                           $request->end);

        return ExamScheduleResource::collection($exam_schedules)->all();
    }

    public function getFixedSchedules (Request $request,
                                               $id_department) : AnonymousResourceCollection
    {
        $fixed_schedules = $this->departmentService->getFixedSchedules($id_department,
                                                                       $request->all());
        return FixedScheduleResource::collection($fixed_schedules);
    }


    public function getModuleClassesByStudySessions (Request $request, $id_department)
    {
        return response($this->departmentService->getModuleClassesByStudySessions($id_department,
                                                                                  $request->term,
                                                                                  $request->ss));
    }

    public function getTeachers ($id_department)
    {
        return $this->departmentService->getTeachers($id_department);
    }
}
