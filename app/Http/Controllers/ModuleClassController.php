<?php

namespace App\Http\Controllers;

use App\Services\Contracts\ModuleClassServiceContract;

class ModuleClassController extends Controller
{
    private ModuleClassServiceContract $moduleClassService;

    /**
     * ModuleClassController constructor.
     * @param ModuleClassServiceContract $moduleClassService
     */
    public function __construct (ModuleClassServiceContract $moduleClassService)
    {
        $this->moduleClassService = $moduleClassService;
    }

    public function getRecentModuleClasses ($id_teacher)
    {
        $data = $this->moduleClassService->getRecentModuleClasses($id_teacher);
        return response($data)->header('Content-Type', 'application/data');
    }
}
