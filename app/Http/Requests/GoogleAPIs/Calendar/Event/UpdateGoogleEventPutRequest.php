<?php

namespace App\Http\Requests\GoogleAPIs\Calendar\Event;

use App\Http\Requests\Abstracts\ACustomFormRequest;

class UpdateGoogleEventPutRequest extends ACustomFormRequest
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
            'conferenceDataVersion' => ['required', 'sometimes', 'integer', 'between:0,1'],
            'maxAttendees'          => ['required', 'sometimes', 'integer'],
            'sendUpdates'           => ['required', 'sometimes', 'string', 'in:all|externalOnly|none'],
            'supportsAttachments'   => ['required', 'sometimes', 'integer'],

            'start'                   => ['required', 'array'],
            'end'                     => ['required', 'array'],
            'anyoneCanAddSelf'        => ['required', 'sometimes', 'integer'],
            'attachments'             => ['required', 'sometimes', 'array'],
            'attendees'               => ['required', 'sometimes', 'array'],
            'attendeesOmitted'        => ['required', 'sometimes', 'boolean'],
            'colorId'                 => ['required', 'sometimes', 'string'],
            'conferenceData'          => ['required', 'sometimes', 'array'],
            'description'             => ['required', 'sometimes', 'string'],
            'extendedProperties'      => ['required', 'sometimes', 'array'],
            'gadget'                  => ['required', 'sometimes', 'array'],
            'guestsCanInviteOthers'   => ['required', 'sometimes', 'boolean'],
            'guestsCanModify'         => ['required', 'sometimes', 'boolean'],
            'guestsCanSeeOtherGuests' => ['required', 'sometimes', 'boolean'],
            'location'                => ['required', 'sometimes', 'string'],
            'originalStartTime'       => ['required', 'sometimes', 'array'],
            'recurrence'              => ['required', 'sometimes', 'array'],
            'reminders'               => ['required', 'sometimes', 'array'],
            'sequence'                => ['required', 'sometimes', 'integer'],
            'source'                  => ['required', 'sometimes', 'array'],
            'status'                  => ['required', 'sometimes', 'string', 'in:confirmed|tentative|cancelled'],
            'summary'                 => ['required', 'sometimes', 'string'],
            'transparency'            => ['required', 'sometimes', 'string', 'in:opaque|transparent'],
            'visibility'              => ['required', 'sometimes', 'string', 'in:default|public|private|confidential'],
        ];
    }
}
