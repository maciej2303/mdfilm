<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'project_id',
        'work_time_type_id',
        'logged_hours',
        'task',
    ];
    protected $with = ['workTimeType'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function workTimeType()
    {
        return $this->belongsTo(WorkTimeType::class);
    }
}
