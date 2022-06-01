<?php

namespace App\Http\Requests\Comments;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function rules()
    {
        return [
            'start_time' => 'nullable|numeric',
            'end_time' => 'nullable|numeric',
            'comment' => 'required|string|max:3000',
        ];
    }
    public function messages()
    {
        return [
            'start_time.numeric' => 'Pole czas od musi być w formacie xx:xx',
            'end_time.numeric' => 'Pole czas do musi być w formacie xx:xx',
        ];
    }
}
