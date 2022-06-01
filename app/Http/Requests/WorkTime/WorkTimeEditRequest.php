<?php

namespace App\Http\Requests\WorkTime;

use Illuminate\Foundation\Http\FormRequest;

class WorkTimeEditRequest extends FormRequest
{
    public function rules()
    {
        return [
            "date_edit"  => "required|date",
            "project_id_edit"  => "required",
            "work_time_type_id_edit"  => "required",
            "logged_hours_edit"  => "required|between:0,24.00",
            "task_edit"  => "required|string|min:1|max:50",
        ];
    }
}
