<?php

namespace App\Http\Requests\Projects;

use Illuminate\Foundation\Http\FormRequest;

class ProjectVersionEditRequest extends FormRequest
{
    public function rules()
    {
        return [
            'version' => 'required|string|max:50|
            unique:project_element_component_versions,version,' . $this->projectElementComponentVersion->id . ',id,project_element_component_id,' . $this->project_element_component_id,
            'pdf' => 'nullable|mimetypes:application/pdf|max:10000',
            'link' => 'nullable|url',
        ];
    }
    public function messages()
    {
        return [
            'pdf.mimetypes' => 'Plik musi być typu PDF',
        ];
    }
}
