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
        $errors = ['errors' => $validator->errors()->toArray()];
        throw new CustomBadHttpRequestException('', $errors, 400);
    }
}