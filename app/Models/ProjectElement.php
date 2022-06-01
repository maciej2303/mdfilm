<?php

namespace App\Models;

use App\Enums\ProjectComponentType;
use App\Enums\ProjectVersionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectElement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'project_id',
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($projectElement) {
            foreach ($projectElement->components as $projectElementComponent) {
                foreach ($projectElementComponent->versions as $version) {
                    $version->delete();
                }
            }
        });
    }

    public function colour()
    {
        $colour = 'primary';
        foreach ($this->components as $component) {
            if(auth()->user()->isClientLevel()) {
                $version = $component->versions()->latest()->where('inner', '=', 0)->first();
                $status = $version != null ? $version->status : null;
                if($status == 'in progress' && !$version->pdf && !$version->youtube_url) {
                    $status = null;
                }
            } else {
                $status = $component->versions()->latest()->first() != null ? $component->versions()->latest()->first()->status : null;
            }
            if ($status == ProjectVersionStatus::ACCEPTED && $colour != 'warning' && $colour != 'grey')
                $colour = 'primary';


            if (($status == ProjectVersionStatus::PENDING || $status == ProjectVersionStatus::CLOSED || $status == null) && $colour != 'warning') {
                $colour = 'grey';
            }

            if ($status == ProjectVersionStatus::SUSPENDED || $status == ProjectVersionStatus::CANCELED) {
                $colour = 'danger';
                break;
            }
            if ($status == ProjectVersionStatus::TO_ACCEPT || $status == ProjectVersionStatus::IN_PROGRESS || $status == ProjectVersionStatus::COMMENTS) {
                $colour = 'warning';
            }
        }
        return $colour;
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function components()
    {
        return $this->hasMany(ProjectElementComponent::class);
    }

    public function componentsInOrder($reverse = true)
    {

        $componentsInOrder = collect();
        $components = $reverse ? array_reverse(ProjectComponentType::getValues()) : ProjectComponentType::getValues();

        foreach ($components as $component) {
            $name = ProjectComponentType::getDescription($component);
            foreach ($this->components as $component) {
                if ($component->name == $name)
                    $componentsInOrder->push($component);
            }
        }
        return $componentsInOrder;
    }
}
