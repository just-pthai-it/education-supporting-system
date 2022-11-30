<?php

namespace App\Http\Requests\GoogleAPIs\Calendar\Event;

use App\Http\Requests\Abstracts\ACustomFormRequest;

class GetGoogleEventGetRequest extends ACustomFormRequest
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
            'iCalUID'                 => ['required', 'sometimes', 'string'],
            'maxAttendees'            => ['required', 'sometimes', 'integer'],
            'orderBy'                 => ['required', 'sometimes', 'string'],
            'privateExtendedProperty' => ['required', 'sometimes', 'string'],
            'q'                       => ['required', 'sometimes', 'string'],
            'sharedExtendedProperty'  => ['required', 'sometimes', 'string'],
            'showDeleted'             => ['required', 'sometimes', 'boolean'],
            'showHiddenInvitations'   => ['required', 'sometimes', 'boolean'],
            'singleEvents'            => ['required', 'sometimes', 'boolean'],
            'timeMax'                 => ['required', 'sometimes', 'date_format:Y-m-d\\TH:i:sP'],
            'timeMin'                 => ['required', 'sometimes', 'date_format:Y-m-d\\TH:i:sP'],
            'timeZone'                => ['required', 'sometimes', 'date_format:P'],
        ];
    }
}
