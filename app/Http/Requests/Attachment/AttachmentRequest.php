<?php

namespace App\Http\Requests\Attachment;

use Illuminate\Foundation\Http\FormRequest;

class AttachmentRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|max:200',
            'description' => 'nullable|max:500',
            'file' => 'required',
        ];
    }
}
