<?php

namespace App\Http\Requests\Calendar;

use Illuminate\Foundation\Http\FormRequest;

class EventEditRequest extends FormRequest
{
    public function rules()
    {
        return [
            'start_date_edit' => 'required|date_format:d.m.Y',
            'end_date_edit' => 'nullable|date_format:d.m.Y',
            'start_time_edit' => 'nullable|date_format:H:i',
            'end_time_edit' => 'nullable|date_format:H:i',
            'event_edit' => 'required|max:60',
            'description_edit' => 'nullable|max:1000'
        ];
    }
}
