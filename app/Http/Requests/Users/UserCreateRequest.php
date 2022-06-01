<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => 'required|max:255|unique:users,email|min:3|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'password' => [
                'required',
                'string',
                'min:4',
                'max:30',
            ],
            'name' => 'required|string|min:4|max:30',
            'level' => 'required',
            'phone_number' => 'nullable|numeric|digits_between:1,30',
            'status' => 'required',
            'avatar' => 'nullable|mimes:jpg,jpeg,png'
        ];
    }
}
