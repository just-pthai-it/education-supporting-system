<?php

namespace App\Services;

use App\Repositories\Contracts\CurriculumRepositoryContract;
use App\Repositories\Contracts\ModuleRepositoryContract;
use Illuminate\Http\JsonResponse;

class CurriculumService implements Contracts\CurriculumServiceContract
{
    private CurriculumRepositoryContract $__curriculumRepository;
    private ModuleRepositoryContract $__moduleRepository;

    /**
     * @param CurriculumRepositoryContract $__curriculumRepository
     * @param ModuleRepositoryContract     $__moduleRepository
     */
    public function __construct (CurriculumRepositoryContract $__curriculumRepository, ModuleRepositoryContract $__moduleRepository)
    {
        $this->__curriculumRepository = $__curriculumRepository;
        $this->__moduleRepository     = $__moduleRepository;
    }

    public function store (array $inputs) : JsonResponse
    {
        $curriculum = $this->__curriculumRepository->insert($inputs);
        if (isset($inputs['modules']))
        {
            $this->__moduleRepository->upsert($inputs['modules']);
            $curriculum->modules()->attach(collect($inputs['modules'])->pluck('id')->toArray());
        }

        return response()->json(['data' => $curriculum], 201);
    }
}