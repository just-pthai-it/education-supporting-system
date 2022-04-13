<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
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

    public function update (Request $request, string $idSchedule)
    {
        Gate::authorize('update-schedule', [$request->all()]);
        $this->scheduleService->update($idSchedule, $request->all());
    }
}
