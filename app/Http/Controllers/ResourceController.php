<?php

namespace App\Http\Controllers;

use App\Services\Contracts\FileUploadServiceContract;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    private FileUploadServiceContract $fileUploadService;

    /**
     * @param FileUploadServiceContract $fileUploadServiceContract
     */
    public function __construct (FileUploadServiceContract $fileUploadServiceContract)
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
}
