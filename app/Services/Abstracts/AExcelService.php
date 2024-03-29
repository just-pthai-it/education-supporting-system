<?php

namespace App\Services\Abstracts;

use DateTime;
use Box\Spout\Reader\ReaderInterface;
use Box\Spout\Common\Exception\IOException;
use App\Services\Contracts\ExcelServiceContract;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Illuminate\Support\Str;

class AExcelService implements ExcelServiceContract
{

    public function readData (string $filePath, array $parameters = []) : array
    {
        return [];
    }

    /**
     * @throws UnsupportedTypeException
     * @throws IOException
     */
    protected function _getReader (string $filePath) : ReaderInterface
    {
        $reader   = ReaderEntityFactory::createReaderFromFile($filePath);
        $reader->setShouldPreserveEmptyRows(true);
        $reader->open($filePath);
        return $reader;
    }

    protected function _getData () : array
    {
        return [];
    }

    protected function _formatData ($rawData) : array
    {
        return [];
    }

    protected function _getCellData (array &$cells, int $index, string $typeCell = '') : string
    {
        if (isset($cells[$index]))
        {
            $value = $cells[$index]->getValue();
            if ($value instanceof DateTime)
            {
                return $value->format('Y-m-d H:i:s');
            }

            if ($typeCell == 'room')
            {
                $value = preg_replace('/ +/', '', Str::upper($value));
                $value = str_replace('NCT', ' NCT', $value);

                if (strpos($value, '-') === false)
                {
                    $value = 'PHTT';
                }
            }

            return $value;
        }

        return '';
    }

    protected function _convertToIdModuleClass (string $idModule, string $moduleClassName) : string
    {
        $array       = explode('-', $moduleClassName);
        $arrayLength = count($array);
        return "{$idModule}-{$array[$arrayLength - 3]}-{$array[$arrayLength - 2]}-{$array[$arrayLength - 1]}";
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