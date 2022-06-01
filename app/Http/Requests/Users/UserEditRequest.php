<?php

namespace App\Http\Requests\Users;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserEditRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => [
                'required',
                'max:255',
                'min:3',
                'email',
                'regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
                'unique:users,email,' . $this->user->id,
            ],
            'password' => [
                'nullable',
                'string',
                'min:4',
                'max:30',

            ],
            'name' => 'required|string|min:4|max:30',
            'level' => 'required',
            'phone_number' => 'nullable|numeric|digits_between:1,30',
            'status' => 'required',
            'avatar' => 'nullable|mimes:jpg,jpeg,png',
        ];
    }
}
