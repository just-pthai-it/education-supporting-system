<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\TrainingTypeResource;
use App\Services\Contracts\TrainingTypeServiceContract;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TrainingTypeController extends Controller
{
    private TrainingTypeServiceContract $trainingTypeService;

    /**
     * @param TrainingTypeServiceContract $trainingTypeService
     */
    public function __construct (TrainingTypeServiceContract $trainingTypeService)
    {
        $this->trainingTypeService = $trainingTypeService;
    }

    public function readMany (Request $request) : AnonymousResourceCollection
    {
        $trainingTypes = $this->trainingTypeService->readMany($request->all());
        return TrainingTypeResource::collection($trainingTypes);
    }
}
