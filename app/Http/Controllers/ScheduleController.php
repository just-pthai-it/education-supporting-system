<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use App\Http\Resources\ScheduleResource;
use App\Services\Contracts\ScheduleServiceContract;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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

    public function readManyByIdDepartment (Request $request,
                                            string  $idDepartment) : AnonymousResourceCollection
    {
        Gate::authorize('get-department-schedule');
        $schedules = $this->scheduleService->readManyByIdDepartment($idDepartment, $request->all());
        return ScheduleResource::collection($schedules);
    }

    public function readManyByIdTeacher (Request $request,
                                         string  $idTeacher) : AnonymousResourceCollection
    {
        Gate::authorize('get-teacher-schedule');
        $schedules = $this->scheduleService->readManyByIdTeacher($idTeacher, $request->all());
        return ScheduleResource::collection($schedules);
    }

    public function update (Request $request, string $idSchedule)
    {
        Gate::authorize('update-schedule', [$request->all()]);
        $this->scheduleService->update($idSchedule, $request->all());
    }
}
