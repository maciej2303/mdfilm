<?php

namespace App\Http\Requests\WorkTime;

use Illuminate\Foundation\Http\FormRequest;

class WorkTimeCreateRequest extends FormRequest
{
    public function rules()
    {
        return [
            "date"    => "required|array",
            "date.*"  => "required|date|",
            "project"    => "required|array",
            "project.*"  => "required|",
            "workTimeType"    => "required|array",
            "workTimeType.*"  => "required|",
            "logged_hours"    => "required|array",
            "logged_hours.*"  => "required|between:0,24.00",
            "task"    => "required|array",
            "task.*"  => "required|string|min:1|max:50",
        ];
    }
}
