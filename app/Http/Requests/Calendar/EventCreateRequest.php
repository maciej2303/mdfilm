<?php

namespace App\Http\Requests\Calendar;

use Illuminate\Foundation\Http\FormRequest;

class EventCreateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'start_date' => 'required|date_format:d.m.Y',
            'end_date' => 'nullable|date_format:d.m.Y',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'event' => 'required|max:60',
            'description' => 'nullable|max:1000'
        ];
    }
}
