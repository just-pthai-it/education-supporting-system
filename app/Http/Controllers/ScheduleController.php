<?php

namespace App\Http\Controllers;

use App\Services\Contracts\ScheduleServiceContract;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    private ScheduleServiceContract $scheduleService;

    /**
     * @param ScheduleServiceContract $scheduleService
     */
    public function __construct (ScheduleServiceContract $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    public function getTeacherSchedules (Request $request, $id_teacher)
    {
        return $this->scheduleService->getTeacherSchedules(auth()->user()->id_user,
                                                           $request->term, $request->ss);
    }

    public function getDepartmentSchedules (Request $request, $id_department)
    {
        return $this->scheduleService->getDepartmentSchedules($id_department,
                                                              $request->term, $request->ss);
    }
}
