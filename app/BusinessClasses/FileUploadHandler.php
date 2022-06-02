<?php

namespace App\BusinessClasses;

use Exception;

class FileUploadHandler
{
    private string $new_file_name;
    private string $old_file_name;

    /**
     * @return string
     */
    public function getNewFileName () : string
    {
        return $this->new_file_name;
    }

    /**
     * @return string
     */
    public function getOldFileName () : string
    {
        return $this->old_file_name;
    }

    public function getFilePath () : string
    {
        return storage_path('app/public/excels/' . $this->new_file_name);
    }
    /**
     * @throws Exception
     */
    public function handleFileUpload ($file)
    {
        $original_file_name = $file->getClientOriginalName();

        $this->old_file_name = $file_name = substr($original_file_name, 0,
                                                   strripos($original_file_name, '.'));
        $expand              = substr($original_file_name, strripos($original_file_name, '.'));

        $new_file_name = preg_replace('/\s+/', '', $file_name);
        $new_file_name = $new_file_name . $expand;

        $location = storage_path('app/public/excels/' . $new_file_name);

        if (move_uploaded_file($file->getRealPath(), $location))
        {
            $this->new_file_name = $new_file_name;
        }
        else
        {
            throw new Exception('File handler exception');
        }
    }
}
