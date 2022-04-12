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

    public function updateMany (Request $request)
    {
        $this->moduleClassService->updateMany($request->all());
    }
}
