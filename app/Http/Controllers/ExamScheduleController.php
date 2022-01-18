<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\TeacherResource;
use App\Http\Resources\ExamScheduleCollection;
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

    public function getTeacherExamSchedules (Request $request, $id_teacher)
    {
        return response($this->examScheduleService->getTeacherExamSchedules(auth()->user()->id_user,
                                                                            $request->term,
                                                                            $request->ss));
    }

    public function getDepartmentExamSchedules (Request $request, $id_department)
    {
        $exam_schedules = $this->examScheduleService->getDepartmentExamSchedules($id_department,
                                                                                 $request->start,
                                                                                 $request->end);
//        var_dump($exam_schedules);
//        return $exam_schedules;
        return TeacherResource::collection($exam_schedules)->all();
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
