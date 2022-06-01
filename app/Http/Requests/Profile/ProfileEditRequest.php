<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class ProfileEditRequest extends FormRequest
{
    public function rules()
    {
        return [
            'password' => [
                'nullable',
                'string',
                'min:4',
                'max:30',

            ],
            'name' => 'required|string|min:4|max:30',
            'phone_number' => 'nullable|numeric|digits_between:1,30',
            'avatar' => 'nullable|mimes:jpg,jpeg,png',
        ];
    }
}
