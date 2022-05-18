<?php

namespace App\Services\Abstracts;

use App\Services\Contracts\ExcelServiceContract;

class AExcelService implements ExcelServiceContract
{

    public function readData (...$parameters) : array
    {
        $rawData = $this->_getData();
        return $this->_formatData($rawData);
    }

    protected function _getData () : array
    {
        return [];
    }

    protected function _formatData ($rawData) : array
    {
        return [];
    }

    public function handleData ($formattedData, ...$parameters)
    {
        // TODO: Implement handleData() method.
    }

    public function setParameters (...$parameters)
    {
        // TODO: Implement setParameters() method.
    }
}