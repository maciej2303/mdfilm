<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectElementComponentVersionAcceptance extends Model
{
    protected $fillable = [
        'project_element_component_version_id',
        'user_id',
    ];
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function version()
    {
        return $this->belongsTo(ProjectElementComponentVersion::class, 'project_element_component_version_id');
    }
}
