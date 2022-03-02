<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $id = $this->fixedScheduleService->createFixedSchedule($request->all());
        return response(['data' => $id], 201);
    }

    public function updateFixedSchedule (Request $request)
    {
        $this->fixedScheduleService->updateFixedSchedule($request->all());
    }

    public function paginateFixedSchedulesByStatus (Request $request) : AnonymousResourceCollection
    {
        $fixedSchedules = $this->fixedScheduleService->paginateFixedSchedulesByStatus($request->status,
                                                                                      $request->pagination);
        return FixedScheduleResource::collection($fixedSchedules);
    }
}
