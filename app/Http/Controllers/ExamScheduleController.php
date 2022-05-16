<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Services\Contracts\ExamScheduleServiceContract;

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

    public function readManyByIdDepartment (Request $request, string $idDepartment)
    {
        Gate::authorize('get-department-exam-schedule');
        return $this->examScheduleService->readManyByIdDepartment($idDepartment, $request->all());
    }

    public function readManyByIdTeacher (Request $request, string $idTeacher)
    {
        Gate::authorize('get-teacher-exam-schedule');
        return $this->examScheduleService->readManyByIdTeacher($idTeacher, $request->all());
    }

    public function update (Request $request)
    {
        Gate::authorize('update-exam-schedule');
        $this->examScheduleService->update($request->all());
    }


    public function updateV1 (Request $request, string $idExamSchedule)
    {
        $this->examScheduleService->updateV1($idExamSchedule, $request->all());
    }

    public function updateProctors (Request $request, string $idExamSchedule)
    {
        $this->examScheduleService->updateProctors($idExamSchedule, $request->all());
    }
}
