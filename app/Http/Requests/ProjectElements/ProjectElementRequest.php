<?php

namespace App\Http\Requests\ProjectElements;

use Illuminate\Foundation\Http\FormRequest;

class ProjectElementRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->projectElement->id ?? null;
        return [
            'name' => 'required|string|max:50|unique:project_elements,name,' . $id . ',id,project_id,' . $this->project_id,
            'components' => 'required',
        ];
    }
}
