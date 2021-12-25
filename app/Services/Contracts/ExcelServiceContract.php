<?php

namespace App\Services\Contracts;

interface ExcelServiceContract
{
    public function readData ($file_name, ...$params);

    public function handleData ($formatted_data, ...$params);

    public function setParameters (...$parameters);
}