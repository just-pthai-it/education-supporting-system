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

    public function updateExamSchedule (Request $request)
    {
        Gate::authorize('update-exam-schedule');
        $this->examScheduleService->updateExamSchedule($request->all());
    }
}
