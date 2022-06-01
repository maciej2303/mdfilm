<?php

namespace App\Http\Requests\Projects;

use Illuminate\Foundation\Http\FormRequest;

class ProjectVersionPDFRequest extends FormRequest
{
    public function rules()
    {
        return [
            'pdf' => 'required|mimetypes:application/pdf|max:10000',
        ];
    }

    public function messages()
    {
        return [
            'pdf.mimetypes' => 'Plik musi być typu PDF',
        ];
    }
}
