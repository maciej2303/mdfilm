<?php

namespace App\Http\Requests\Projects;

use Illuminate\Foundation\Http\FormRequest;

class ProjectElementComponentVersionRequest extends FormRequest
{
    public function rules()
    {
        return [
            'version' => 'required|string|max:50|unique:project_element_component_versions,version,null,id,project_element_component_id,' . $this->project_element_component_id,
        ];
    }
}
