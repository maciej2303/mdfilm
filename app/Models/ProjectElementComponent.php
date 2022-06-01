<?php

namespace App\Models;

use App\Enums\ProjectVersionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectElementComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'project_element_id',
    ];


    public function projectElement()
    {
        return $this->belongsTo(ProjectElement::class);
    }

    public function project()
    {
        return $this->projectElement->project;
    }


    public function versions()
    {
        return $this->hasMany(ProjectElementComponentVersion::class);
    }

    public function versionsDesc()
    {
        return $this->versions()->sortBy('created_at', 'desc');
    }

    public function externalVersions()
    {
        return $this->versions()->where('inner', 0);
    }
    public function externalVersionsCompleteVerisons()
    {
        return $this->versions()->where('inner', 0)->where(function($query) {
            $query->whereNotNull('pdf');
            $query->orWhereNotNull('youtube_url');
        });
    }

    public function innerVersions()
    {
        return $this->versions()->where('inner', 1);
    }

    public function setVersionsActiveToFalse()
    {
        foreach ($this->versions as $version) {
            $version->active = 0;
            $version->status = ProjectVersionStatus::CLOSED;
            $version->save();
        }
    }
}
