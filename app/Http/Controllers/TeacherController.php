<?php

namespace App\Http\Controllers;

use App\Http\Resources\TeacherResource;
use App\Services\Contracts\TeacherServiceContract;

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
}
