<?php

namespace App\Http\Requests;

use App\Models\Role;
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
        $permissions = Role::find($this->user()->id_role)->permissions()
                           ->pluck('permissions.id')->toArray();

        if ($this->all()['type'] == 'accept' && in_array(13, $permissions))
        {
            return true;
        }

        if ($this->all()['type'] == 'set_room' && in_array(17, $permissions))
        {
            return true;
        }

        if ($this->all()['type'] == 'deny' &&
            (in_array(17, $permissions) || in_array(13, $permissions)))
        {
            return true;
        }

        if ($this->all()['type'] == 'cancel' && in_array(32, $permissions))
        {
            return true;
        }

        return false;
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
