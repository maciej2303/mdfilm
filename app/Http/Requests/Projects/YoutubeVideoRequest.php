<?php

namespace App\Http\Requests\Projects;

use Illuminate\Foundation\Http\FormRequest;

class YoutubeVideoRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => 'required|max:70',
            'description' => 'required|max:5000',
            'storage_path' => 'required',
        ];
    }
}
