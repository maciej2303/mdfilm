<?php

namespace App\Models;

use App\Enums\UserLevel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'client_id',
        'project_description',
        'term',
        'project_status_id',
        'simple',
        'hashed_url',
    ];

    protected $with = ['client'];

    protected $casts = [
        'term' => 'date',
    ];

    protected $appends = [
        'created'
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($project) {
            $folder = 'project-' . $project->hashed_url;
            Storage::disk('s3')->deleteDirectory($folder);
            $project->comments()->delete();
            $project->attachments()->delete();
            foreach ($project->projectElements as $projectElement)
                foreach ($projectElement->components as $projectElementComponent) {
                    foreach ($projectElementComponent->versions as $version) {
                        $version->delete();
                    }
                }
        });
    }

    public function getCreatedATAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('d.m.Y');
    }


    public function getCreatedAttribute()
    {
        $created = Carbon::parse($this->attributes['created_at'])->format('d.m.Y');
        return $created;
    }

    public function getTermAttribute()
    {
        $term = Carbon::parse($this->attributes['term'])->format('d.m.Y');
        return $term;
    }

    //SCOPES

    public function scopeArchived($query)
    {
        return $query->whereNotNull('archived_at');
    }

    public function scopeActive($query)
    {
        return $query->whereNull('archived_at');
    }

    public function scopeInactive($query)
    {
        return $query->whereNotNull('archived_at');
    }

    //RELATIONS
    public function contactPersons()
    {
        return $this->belongsToMany(User::class, 'contact_person_project', 'project_id', 'contact_person_id');
    }

    public function teamMembers()
    {
        return $this->belongsToMany(User::class, 'project_team_member', 'project_id', 'team_member_id');
    }

    public function managers()
    {
        return $this->belongsToMany(User::class, 'manager_project', 'project_id', 'manager_id');
    }

    public function partners()
    {
        return $this->belongsToMany(User::class, 'partner_project', 'project_id', 'partner_id');
    }

    public function allMembers()
    {
        return $this->contactPersons->merge($this->teamMembers->merge($this->managers->merge($this->partners)))->unique();
    }

    public function workers()
    {
        return $this->teamMembers->merge($this->managers)->unique();
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function projectStatus()
    {
        return $this->belongsTo(ProjectStatus::class);
    }

    public function workTimes()
    {
        return $this->hasMany(WorkTime::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'relationable');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'relationable');
    }

    public function externalComments()
    {
        return $this->comments()->where('inner', 0);
    }

    public function innerComments()
    {
        return $this->comments()->where('inner', 1);
    }

    public function projectElements()
    {
        return $this->hasMany(ProjectElement::class);
    }

    public function tasks()
    {
        return $this->morphMany(Task::class, 'relationable')->orderBy('order', 'asc');
    }

    public function changeLogs()
    {
        return $this->morphMany(ChangeLog::class, 'relationable');
    }

    public function taskChangeLogs()
    {
        return $this->morphMany(ChangeLog::class, 'relationable')->where('model_type', Task::class);
    }

    public function getLatestVersionInSimpleProject($inner = 0)
    {
        $version = null;
        try {
            $version = $this->projectElements()->latest()->first()->components()->latest()->first();
            if ($inner == 0)
                $version = $version->externalVersions()->latest()->first();
            else
                $version = $version->versions()->latest()->first();
        } catch (\Throwable $th) {
            //throw $th;
        }


        return $version;
    }

    public function getAllVersionsIdAndStatusInProject()
    {
        $versions = DB::table('projects')
            ->join('project_elements', 'projects.id', '=', 'project_elements.project_id')
            ->join('project_element_components', 'project_elements.id', '=', 'project_element_components.project_element_id')
            ->join('project_element_component_versions', 'project_element_components.id', '=', 'project_element_component_versions.project_element_component_id')
            ->where('projects.id', $this->id)
            ->select('project_element_component_versions.id', 'project_element_component_versions.status')
            ->get();

        return $versions;
    }

    public function allComments($external = false)
    {
        $comments = DB::table('projects')
            ->join('project_elements', 'projects.id', '=', 'project_elements.project_id')
            ->join('project_element_components', 'project_elements.id', '=', 'project_element_components.project_element_id')
            ->join('project_element_component_versions', function ($join) use ($external) {
                $join->on('project_element_components.id', '=', 'project_element_component_versions.project_element_component_id')
                    ->when($external, function ($q) {
                        return $q->where('project_element_component_versions.inner', '=', 0);
                    });
            })
            ->join('comments', function ($join) use ($external) {
                $join->on('project_element_component_versions.id', '=', 'comments.relationable_id')
                    ->when($external, function ($q) {
                        return $q->where('comments.inner', '=', 0);
                    })
                    ->where('comments.relationable_type', '=', ProjectElementComponentVersion::class);
            })
            ->where('projects.id', $this->id)
            ->select('comments.id')
            ->get();

        return $comments->pluck('id');
    }

    //FUNCTIONS
    public function allAttachments($inner = 0)
    {
        $attachments = DB::table('projects')
            ->leftJoin('project_elements', 'projects.id', '=', 'project_elements.project_id')
            ->leftJoin('project_element_components', 'project_elements.id', '=', 'project_element_components.project_element_id')
            ->leftJoin('project_element_component_versions', function ($join) use ($inner) {
                $join->on('project_element_components.id', '=', 'project_element_component_versions.project_element_component_id')
                    ->when(($inner == 0), function ($q) {
                        return $q->where('project_element_component_versions.inner', '=', 0);
                    });
            })
            ->join('attachments', function ($join) {
                $join
                    ->on(function ($query) {
                        $query->on('project_element_component_versions.id', '=', 'attachments.relationable_id')
                            ->where('attachments.relationable_type', '=', ProjectElementComponentVersion::class);
                    })
                    ->orOn(function ($query) {
                        $query->on('projects.id', '=', 'attachments.relationable_id')
                            ->where('attachments.relationable_type', '=', Project::class);
                    });
            })
            ->where('projects.id', $this->id)
            ->select('attachments.id')
            ->get()->unique();

        return $attachments->pluck('id');
    }

    public function unreadCommentsCount($userId)
    {
        $user = User::find($userId);
        $readComments = $user->readComments->pluck('id');
        $commentsCount = 0;
        $comments = $user->level == UserLevel::CLIENT ? $this->allComments(true) : $this->allComments();
        $projectComments = $user->level == UserLevel::CLIENT ? $this->externalComments->pluck('id') : $this->comments->pluck('id');
        $comments = $comments->merge($projectComments);
        foreach ($comments as $comment) {
            if (!$readComments->contains($comment)) {
                $commentsCount++;
            }
        }
        return $commentsCount;
    }

    public function doUserHaveAccess($userId)
    {
        $allMembers = $this->allMembers()->pluck('id');
        if ($allMembers->contains($userId) || User::find($userId)->level == UserLevel::ADMIN)
            return true;
        return false;
    }
}
