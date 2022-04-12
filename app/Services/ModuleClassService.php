<?php

namespace App\Services;

use Illuminate\Support\Arr;
use App\Repositories\Contracts\ModuleClassRepositoryContract;

class ModuleClassService implements Contracts\ModuleClassServiceContract
{
    private ModuleClassRepositoryContract $moduleClassDepository;

    /**
     * @param ModuleClassRepositoryContract  $moduleClassDepository
     */
    public function __construct (ModuleClassRepositoryContract  $moduleClassDepository)
    {
        $this->moduleClassDepository  = $moduleClassDepository;
    }

    public function updateMany (array $inputs)
    {
        $this->moduleClassDepository->update(Arr::except($inputs, ['ids']),
                                             [['id', 'in', $inputs['ids']]]);
    }
}
