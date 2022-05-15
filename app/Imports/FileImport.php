<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithColumnLimit;

class FileImport implements ToCollection, WithColumnLimit, WithLimit
{
    use Importable;

    /**
     * @param Collection $collection
     */
    public function collection (Collection $collection)
    {

    }

    public function endColumn () : string
    {
        return 'T';
    }

    public function limit () : int
    {
        return 500;
    }
}
