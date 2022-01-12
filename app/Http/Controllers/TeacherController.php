<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\FixedScheduleResource;
use App\Services\Contracts\TeacherServiceContract;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TeacherController extends Controller
{
    private TeacherServiceContract $teacherService;

    /**
     * @param TeacherServiceContract $teacherService
     */
    public function __construct (TeacherServiceContract $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    public function getTeachersByIdDepartment ($id_department)
    {
        return response($this->teacherService->getTeachersByIdDepartment($id_department));
    }

    public function getFixedSchedulesByStatus (Request $request) : AnonymousResourceCollection
    {
        $fixed_schedules = $this->teacherService->getFixedSchedulesByStatus(auth()->user()->id_user,
                                                                            $request->status);
        return FixedScheduleResource::collection($fixed_schedules);
    }
}
