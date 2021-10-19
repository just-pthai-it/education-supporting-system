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

    public function uploadRollCallFile (Request $request, $id_training_type)
    {
        $this->fileUploadService->importRollCallFile($request->file, $id_training_type);
    }

    public function uploadScheduleFile (Request $request)
    {

    }
}
