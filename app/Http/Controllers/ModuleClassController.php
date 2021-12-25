<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Contracts\ModuleClassServiceContract;

class ModuleClassController extends Controller
{
    private ModuleClassServiceContract $moduleClassService;

    /**
     * @param ModuleClassServiceContract $moduleClassService
     */
    public function __construct (ModuleClassServiceContract $moduleClassService)
    {
        $this->moduleClassService = $moduleClassService;
    }

    public function updateModuleClass (Request $request)
    {
        $this->moduleClassService->updateModuleClass($request->only([
                                                                        'id',
                                                                        'name',
                                                                        'number_plan',
                                                                        'number_reality',
                                                                        'id_study_session',
                                                                        'is_international',
                                                                        'id_module',
                                                                        'id_teacher',
                                                                    ]));
    }

    public function getModuleClassesByIdTeacher (Request $request, $id_teacher)
    {
        return response($this->moduleClassService->getModuleClassesByIdTeacher(auth()->user()->id_user,
                                                                                  $request->term,
                                                                                  $request->ss));
    }

    public function getModuleClassesByIdDepartment (Request $request, $id_department)
    {
        return response($this->moduleClassService->getModuleClassesByIdDepartment($id_department,
                                                                                  $request->term,
                                                                                  $request->ss));
    }
}
