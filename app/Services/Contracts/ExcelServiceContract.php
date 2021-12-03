<?php

namespace App\Services\Contracts;

interface ExcelServiceContract
{
    public function readData ($file_name);

    public function handleData ($formatted_data);

    public function setParameters (...$parameters);
}