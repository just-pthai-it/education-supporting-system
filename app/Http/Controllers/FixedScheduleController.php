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

    public function read (Request $request) : AnonymousResourceCollection
    {
        Gate::authorize('get-fixed-schedule');
        $fixedSchedules = $this->fixedScheduleService->read($request->all());
        return FixedScheduleResource::collection($fixedSchedules);
    }
}
