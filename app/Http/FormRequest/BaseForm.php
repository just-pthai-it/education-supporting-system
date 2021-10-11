<?php

namespace App\Http\FormRequest;

use App\Exceptions\InvalidFormRequestException;
use Exception;
use Illuminate\Http\Request;

abstract class BaseForm
{
    abstract protected function getRules ();

    /**
     * @throws InvalidFormRequestException
     */
    public function validate (Request $request)
    {
        try
        {
            $request->validate($this->getRules());
        }
        catch (Exception $exception)
        {
            throw new InvalidFormRequestException();
        }
    }
}
