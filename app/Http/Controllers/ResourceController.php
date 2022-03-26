<?php

namespace App\Http\Controllers;

use App\Services\Contracts\ResourceServiceContract;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    private ResourceServiceContract $fileUploadService;

    /**
     * @param ResourceServiceContract $fileUploadServiceContract
     */
    public function __construct (ResourceServiceContract $fileUploadServiceContract)
    {
        $this->fileUploadService = $fileUploadServiceContract;
    }

    public function uploadRollCallFile (Request $request)
    {
        $this->fileUploadService->importRollCallFile($request->all());
    }

    public function uploadScheduleFile (Request $request)
    {
        $this->fileUploadService->importScheduleFile($request->all());
    }

    public function uploadExamScheduleFile(Request $request)
    {
        $this->fileUploadService->importExamScheduleFile($request->all());
    }

    public function uploadCurriculumFile(Request $request)
    {
        $this->fileUploadService->importCurriculumFile($request->all());
    }
}
