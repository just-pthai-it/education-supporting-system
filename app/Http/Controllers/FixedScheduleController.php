<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\FixedScheduleResource;
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

    public function create (Request $request)
    {
        Gate::authorize('create-fixed-schedule', [$request->all()]);
        $id = $this->fixedScheduleService->create($request->all());
        return response(['data' => $id], 201);
    }

    public function update (Request $request)
    {
        Gate::authorize('update-fixed-schedule', [$request->all()]);
        $this->fixedScheduleService->update($request->all());
    }
}
