<?php

namespace App\Http\Requests\Projects;

use Illuminate\Foundation\Http\FormRequest;

class ProjectCreateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|min:3|max:50',
            'client_id' => 'required',
            'project_description' => 'nullable|max:1000',
            'term' => 'required|date',
            'project_status_id' => 'required',
            'simple' => 'required',
        ];
    }
}
