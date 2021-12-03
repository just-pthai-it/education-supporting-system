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

    public function getTeacherSchedules ($id_teacher)
    {
        return $this->scheduleService->getTeacherSchedules(auth()->user()->id_user);
    }

    public function getDepartmentSchedules ($id_department)
    {
        return $this->scheduleService->getDepartmentSchedules(auth()->user()->id_user);
    }
}
