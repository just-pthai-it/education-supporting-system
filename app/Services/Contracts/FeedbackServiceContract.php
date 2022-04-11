<?php

namespace App\Services\Contracts;

interface FeedbackServiceContract
{
    public function create ($feedback);

    public function readMany (array $inputs);
}