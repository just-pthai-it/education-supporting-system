<?php

namespace App\Http\Requests\Abstracts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\CustomBadHttpRequestException;

abstract class ACustomFormRequest extends FormRequest
{
    /**
     * @throws CustomBadHttpRequestException
     */
    protected function failedValidation (Validator $validator)
    {
        $messages = json_encode($validator->errors()->toArray());
        throw new CustomBadHttpRequestException($messages, 400);
    }
}