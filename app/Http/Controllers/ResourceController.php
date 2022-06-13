<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportSchedulePostRequest;
use App\Http\Requests\ImportRollCallPostRequest;
use App\Services\Contracts\ResourceServiceContract;
use Illuminate\Http\Request;
use App\Http\Requests\ImportExamSchedulePostRequest;

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

    public function uploadRollCallFile (ImportRollCallPostRequest $request)
    {
        $this->fileUploadService->importRollCallFile($request->validated());
    }

    public function uploadScheduleFile (ImportSchedulePostRequest $request)
    {
        $this->fileUploadService->importScheduleFile($request->validated());
    }

    public function uploadExamScheduleFile (ImportExamSchedulePostRequest $request)
    {
        $this->fileUploadService->importExamScheduleFile($request->validated());
    }

    public function uploadCurriculumFile (Request $request)
    {
        $this->fileUploadService->importCurriculumFile($request->all());
    }
}
