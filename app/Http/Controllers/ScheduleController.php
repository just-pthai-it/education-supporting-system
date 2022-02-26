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

    public function updateSchedules (Request $request)
    {
        $this->scheduleService->updateSchedules($request->all());
    }
}
