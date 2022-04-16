<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FixedSchedulePatchRequest extends FormRequest
{
    protected $redirect = '/api/bad-request';

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
            'type'        => 'required',
            'new_id_room' => 'required_if:type,set_room',
            'reason_deny' => 'required_if:type,deny',
            'accepted_at' => 'required_if:type,accept',
            'set_room_at' => 'required_if:type,set_room',
        ];
    }
}
