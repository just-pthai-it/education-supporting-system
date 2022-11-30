<?php

namespace App\Http\Requests\GoogleAPIs\Calendar\Event;

use App\Http\Requests\Abstracts\ACustomFormRequest;

class DestroyGoogleEventDeleteRequest extends ACustomFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize () : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules () : array
    {
        return [
            'sendUpdates' => ['required', 'sometimes', 'string', 'in:all|externalOnly|none']
        ];
    }
}
