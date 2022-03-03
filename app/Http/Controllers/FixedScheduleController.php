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

    public function createFixedSchedule (Request $request)
    {
        Gate::authorize('create-fixed-schedule', [$request->all()]);
        $id = $this->fixedScheduleService->createFixedSchedule($request->all());
        return response(['data' => $id], 201);
    }

    public function updateFixedSchedule (Request $request)
    {
        Gate::authorize('update-fixed-schedule', [$request->all()]);
        $this->fixedScheduleService->updateFixedSchedule($request->all());
    }

    public function paginateFixedSchedulesByStatus (Request $request) : AnonymousResourceCollection
    {
        Gate::authorize('get-fixed-schedule');
        $fixedSchedules = $this->fixedScheduleService->paginateFixedSchedulesByStatus($request->status,
                                                                                      $request->pagination);
        return FixedScheduleResource::collection($fixedSchedules);
    }
}
