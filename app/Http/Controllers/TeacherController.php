<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\TeacherResource;
use App\Http\Resources\ExamScheduleResource;
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

    public function read (string $idTeacher) : TeacherResource
    {
        return new TeacherResource($this->teacherService->read($idTeacher));
    }

    public function getExamSchedules (Request $request,
                                      string  $idTeacher) : AnonymousResourceCollection
    {
        Gate::authorize('get-teacher-exam-schedule');
        $exam_schedules = $this->teacherService->getExamSchedules($idTeacher, $request->all());
        return ExamScheduleResource::collection($exam_schedules);
    }
}
