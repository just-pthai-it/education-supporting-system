<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\ExamScheduleResource;
use App\Services\Contracts\ExamScheduleServiceContract;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ExamScheduleController extends Controller
{
    private ExamScheduleServiceContract $examScheduleService;

    /**
     * @param ExamScheduleServiceContract $examScheduleService
     */
    public function __construct (ExamScheduleServiceContract $examScheduleService)
    {
        $this->examScheduleService = $examScheduleService;
    }

    public function readManyByIdDepartment (Request $request,
                                            string  $idDepartment) : AnonymousResourceCollection
    {
        Gate::authorize('get-department-exam-schedule');
        $exam_schedules = $this->examScheduleService->readManyByIdDepartment($idDepartment,
                                                                             $request->all());
        return ExamScheduleResource::collection($exam_schedules);
    }

    public function readManyByIdTeacher (Request $request,
                                         string  $idTeacher) : AnonymousResourceCollection
    {
        Gate::authorize('get-teacher-exam-schedule');
        $exam_schedules = $this->examScheduleService->readManyByIdTeacher($idTeacher,
                                                                          $request->all());
        return ExamScheduleResource::collection($exam_schedules);
    }

    public function update (Request $request)
    {
        Gate::authorize('update-exam-schedule');
        $this->examScheduleService->update($request->all());
    }

    public function updateProctors (Request $request, string $idExamSchedule)
    {
        $this->examScheduleService->updateProctors($idExamSchedule, $request->all());
    }
}
