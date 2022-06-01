<?php

namespace App\Http\Requests\ProjectStatuses;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Lang;

class ProjectStatusCreateRequest extends FormRequest
{
    public function rules()
    {
        $langs = Lang::getLangs();
        $rules = [
            'colour' => 'required|string|max:10',
        ];
        foreach($langs as $lang) {
            $field_name = 'name.'.$lang;
            $rules[$field_name] = 'required|string|max:30';
        }
        return $rules;
    }
}
