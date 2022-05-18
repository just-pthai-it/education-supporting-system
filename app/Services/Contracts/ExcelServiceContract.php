<?php

namespace App\Services\Contracts;

interface ExcelServiceContract
{
    public function readData (...$parameters);

    public function handleData ($formattedData, ...$parameters);

    public function setParameters (...$parameters);
}