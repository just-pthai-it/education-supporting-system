<?php

namespace App\Services;

use App\Repositories\Contracts\ModuleRepositoryContract;
use Illuminate\Http\JsonResponse;

class ModuleService implements Contracts\ModuleServiceContract
{
    private ModuleRepositoryContract $__moduleRepository;

    /**
     * @param ModuleRepositoryContract $moduleDepository
     */
    public function __construct (ModuleRepositoryContract $moduleDepository)
    {
        $this->__moduleRepository = $moduleDepository;
    }

    public function store (array $inputs) : JsonResponse
    {
        $module = $this->__moduleRepository->findById($inputs['id']);

        if ($module == null)
        {
            $module = $this->__moduleRepository->insert($inputs);
            if (isset($inputs['id_curriculum']))
            {
                $module->curriculums()->attach($inputs['id_curriculum']);
            }
            return response()->json(['data' => $module], 201);
        }

        return response()->json(['data' => $module], 409);

    }
}