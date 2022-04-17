<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\FixedScheduleResource;
use App\Http\Requests\FixedSchedulePostRequest;
use App\Http\Requests\FixedSchedulePatchRequest;
use App\Services\Contracts\FixedScheduleServiceContract;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FixedScheduleController extends Controller
{
    private FixedScheduleServiceContract $fixedScheduleService;

    /**
     * @param FixedScheduleServiceContract $fixedScheduleService
     */
    public function __construct (FixedScheduleServiceContract $fixedScheduleService)
    {
        $this->fixedScheduleService = $fixedScheduleService;
    }

    public function readMany (Request $request) : AnonymousResourceCollection
    {
        Gate::authorize('get-fixed-schedule');
        $fixedSchedules = $this->fixedScheduleService->readMany($request->all());
        return FixedScheduleResource::collection($fixedSchedules);
    }

    public function readManyByIdDepartment (Request $request,
                                            string  $idDepartment) : AnonymousResourceCollection
    {
        Gate::authorize('get-department-fixed-schedule', [$request->all()]);
        $fixed_schedules = $this->fixedScheduleService->readManyByIdDepartment($idDepartment,
                                                                               $request->all());
        return FixedScheduleResource::collection($fixed_schedules);
    }

    public function readManyByIdTeacher (Request $request,
                                         string  $idTeacher) : AnonymousResourceCollection
    {
        Gate::authorize('get-teacher-fixed-schedule');
        $fixedSchedules = $this->fixedScheduleService->readManyByIdTeacher($idTeacher,
                                                                           $request->all());
        return FixedScheduleResource::collection($fixedSchedules);
    }

    public function create (FixedSchedulePostRequest $request)
    {
        $id = $this->fixedScheduleService->create($request->validated());
        return response(['data' => $id], 201);
    }

    public function update (FixedSchedulePatchRequest $request, string $idFixedSchedule)
    {
        $this->fixedScheduleService->update($idFixedSchedule, $request->validated());
    }
}
