<?php

namespace App\Http\Controllers;

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

    public function getRecentAcademicYears ()
    {
        $data = $this->academicYearService->getRecentAcademicYears();
        return response($data)->header('Content-Type', 'application/data');
    }
}
