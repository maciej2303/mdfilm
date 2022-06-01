<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'user_id',
        'relationable_id',
        'relationable_type'
    ];

    protected $with = [
        'changeLogs'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function version()
    {
        return $this->belongsTo(ProjectElementComponentVersion::class, 'project_element_component_version_id');
    }

    public function changeLogs()
    {
        return $this->morphMany(ChangeLog::class, 'model');
    }

    public function relationable()
    {
        return $this->morphTo();
    }
}
