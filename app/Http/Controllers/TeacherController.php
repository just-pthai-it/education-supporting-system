<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\TeacherResource;
use App\Services\Contracts\TeacherServiceContract;
use App\Http\Requests\UpdateExamScheduleTeacherPatchRequest;

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

    public function read (Request $request, string $idTeacher) : TeacherResource
    {
        return new TeacherResource($this->teacherService->read($idTeacher));
    }

    public function readMany (Request $request)
    {
        return $this->teacherService->readMany($request->all());
    }

    public function updateExamScheduleTeacherByIdExamSchedule (UpdateExamScheduleTeacherPatchRequest $request,
                                                               string                                $idTeacher,
                                                               string                                $idExamSchedule)
    {
        $this->teacherService->updateExamScheduleTeacherByIdExamSchedule($idTeacher,
                                                                         $idExamSchedule,
                                                                         $request->validated());
    }

}
