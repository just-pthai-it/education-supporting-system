<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Contracts\AcademicYearServiceContract;

class AcademicYearController extends Controller
{
    private AcademicYearServiceContract $academicYearService;

    /**
     * @param AcademicYearServiceContract $academicYearService
     */
    public function __construct (AcademicYearServiceContract $academicYearService)
    {
        $this->academicYearService = $academicYearService;
    }

    public function readMany (Request $request)
    {
        return $this->academicYearService->readMany($request->all());
    }
}
