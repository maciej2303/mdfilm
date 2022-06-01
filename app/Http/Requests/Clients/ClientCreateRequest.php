<?php

namespace App\Http\Requests\Clients;

use Illuminate\Foundation\Http\FormRequest;

class ClientCreateRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge(['billing_emails' => explode(';', $this->billing_emails)]);
        $this->merge(['contact_emails' => explode(';', $this->contact_emails)]);
    }


    public function rules()
    {
        return [
            'name' => 'required|string|min:3|max:50|unique:clients,name',
            'nip' => 'required|numeric|digits:10',
            'address' => 'nullable|max:255',
            'contact_person' => 'nullable|max:50',
            'billing_emails.*' => 'email|max:255|distinct|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'contact_emails.*' => 'email|max:255|distinct|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'phone_number' => 'nullable|numeric|digits_between:1,30',
            'additional_informations' => 'nullable|max:1000',
            'status' => 'required'
        ];
    }
}
