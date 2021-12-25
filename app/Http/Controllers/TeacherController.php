<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    public function getTeachersByIdDepartment (Request $request)
    {
        return response($this->teacherService->getTeachersByIdDepartment($request->id_department));
    }
}
