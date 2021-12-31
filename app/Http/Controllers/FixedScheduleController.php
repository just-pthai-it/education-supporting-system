<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Contracts\FixedScheduleServiceContract;

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

    public function createFixedSchedule(Request $request)
    {
        $this->fixedScheduleService->createFixedSchedule($request->only([
                                                                            'id_schedule',
                                                                            'new_date',
                                                                            'new_shift',
                                                                            'new_id_room',
                                                                            'old_date',
                                                                            'old_shift',
                                                                            'old_id_room',
                                                                            'time_request',
                                                                            'time_set_room',
                                                                        ]));
    }
}
