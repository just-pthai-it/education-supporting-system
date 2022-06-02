<?php

namespace App\Services\Contracts;

interface ExcelServiceContract
{
    public function readData (string $filePath, ...$parameters);

    public function handleData ($formattedData, ...$parameters);

    public function setParameters (...$parameters);
}