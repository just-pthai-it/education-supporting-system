<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    public function getTeacherExamSchedules ($id_teacher)
    {
        return response($this->examScheduleService->getTeacherExamSchedules(auth()->user()->id_user));
    }

    public function getDepartmentExamSchedules ($id_department)
    {
        return response($this->examScheduleService->getDepartmentExamSchedules($id_department));
    }

    public function updateExamSchedule (Request $request)
    {
        $this->examScheduleService->updateExamSchedule($request->only(['id',
                                                                       'id_module_class',
                                                                       'method',
                                                                       'time_start',
                                                                       'time_end',
                                                                       'id_room',
                                                                       'note',
                                                                      ]));
    }
}
