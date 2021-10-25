<?php

namespace App\Http\Controllers;

use App\Services\Contracts\SchoolYearServiceContract;
use Illuminate\Http\Request;

class SchoolYearController extends Controller
{
    private SchoolYearServiceContract $schoolYearService;

    /**
     * @param SchoolYearServiceContract $schoolYearService
     */
    public function __construct (SchoolYearServiceContract $schoolYearService)
    {
        $this->schoolYearService = $schoolYearService;
    }

    public function getSchoolYears ()
    {

    }
}
