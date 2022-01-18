<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ScheduleResource;
use App\Http\Resources\ExamScheduleResource;
use App\Http\Resources\FixedScheduleResource;
use App\Services\Contracts\TeacherServiceContract;
use App\Services\Contracts\ScheduleServiceContract;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TeacherController extends Controller
{
    private TeacherServiceContract $teacherService;
    private ScheduleServiceContract $scheduleService;

    /**
     * @param TeacherServiceContract  $teacherService
     * @param ScheduleServiceContract $scheduleService
     */
    public function __construct (TeacherServiceContract  $teacherService,
                                 ScheduleServiceContract $scheduleService)
    {
        $this->teacherService  = $teacherService;
        $this->scheduleService = $scheduleService;
    }

    public function getTeachersByIdDepartment ($id_department)
    {
        return response($this->teacherService->getTeachersByIdDepartment($id_department));
    }

    public function getSchedules (Request $request, $id_teacher)
    {
        $schedules = $this->scheduleService->getTeacherSchedules(auth()->user()->id_user,
                                                                 $request->start,
                                                                 $request->end);
        return ScheduleResource::collection($schedules)->all();
    }

    public function getExamSchedules (Request $request, $id_teacher)
    {
        $exam_schedules = $this->teacherService->getExamSchedules(auth()->user()->id_user,
                                                                  $request->start,
                                                                  $request->end);
        return ExamScheduleResource::collection($exam_schedules)->all();
    }

    public function getFixedSchedulesByStatus (Request $request) : AnonymousResourceCollection
    {
        $fixed_schedules = $this->teacherService->getFixedSchedulesByStatus(auth()->user()->id_user,
                                                                            $request->status);
        return FixedScheduleResource::collection($fixed_schedules);
    }
}
