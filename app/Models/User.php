<?php

namespace App\Models;

use App\Enums\ClientStatus;
use App\Enums\ProjectVersionStatus;
use App\Enums\UserLevel;
use App\Enums\UserStatus;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'level',
        'status',
        'client_id',
        'who_add',
        'avatar',

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['translated_level'];

    public function getTranslatedLevelAttribute()
    {
        return UserLevel::getDescription($this->level);
    }
    public function setPasswordAttribute($pass)
    {
        if ($pass != null)
            $this->attributes['password'] = Hash::make($pass);
    }

    //SCOPES

    public function scopeClients(Builder $query)
    {
        return $query
            ->where('level', UserLevel::CLIENT);
    }

    public function scopeAdminsAndWorkers(Builder $query)
    {
        return $query
            ->where('level', UserLevel::ADMIN)
            ->orWhere('level', UserLevel::WORKER);
    }

    //functions
    public function workTimesByMonth($month = null, $year = null)
    {
        return $this->workTimes()->whereMonth('created_at', $month ?? Carbon::now()->month)->whereYear('created_at', $year ?? Carbon::now()->year);
    }

    public function isAdmin()
    {
        if ($this->level == UserLevel::ADMIN)
            return true;

        return false;
    }

    public function isActive()
    {
        if ($this->status == UserStatus::INACTIVE || ($this->level == UserLevel::CLIENT && $this->client->status == ClientStatus::INACTIVE)) {
            return false;
        }
        return true;
    }
    public function isClientLevel() 
    {
        return $this->level == \App\Enums\UserLevel::CLIENT;
    }
    //RELATIONS
    public function whoAdd()
    {
        return $this->belongsTo(User::class, 'who_add');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function contactProjects()
    {
        return $this->belongsToMany(Project::class, 'contact_person_project', 'contact_person_id', 'project_id');
    }

    public function managedProjects()
    {
        return $this->belongsToMany(Project::class, 'manager_project', 'manager_id', 'project_id');
    }
    public function participatedProjects()
    {
        return $this->belongsToMany(Project::class, 'project_team_member', 'team_member_id', 'project_id');
    }
    public function partnerProjects()
    {
        return $this->belongsToMany(Project::class, 'partner_project', 'partner_id', 'project_id');
    }
    public function getAllUserProjects()
    {
        return $this->contactProjects->merge($this->managedProjects->merge($this->participatedProjects->merge($this->partnerProjects)))->unique();
    }

    public function workTimes()
    {
        return $this->hasMany(WorkTime::class);
    }
    public function comments()
    {
        return $this->morphMany(Comment::class, 'authorable');
    }
    public function readComments()
    {
        return $this->belongsToMany(Comment::class, 'read_comments', 'user_id', 'comment_id'); //
    }

    public function acceptances()
    {
        return $this->hasMany(ProjectElementComponentVersionAcceptance::class);
    }

    public function unacceptedVersions($projectId)
    {
        $unacceptedVersions =  $this->acceptances()->where('acceptance', 0)->with('version')->get()->pluck('version');
        $unacceptedVersions = $unacceptedVersions->filter(function ($version) use ($projectId) {
            $project = $version->project();
            return $project->id == $projectId && $version->active == 1 && $version->status == ProjectVersionStatus::TO_ACCEPT;
        });
        return $unacceptedVersions;
    }

    public function unreadCommentsCount()
    {
        //Client have access only to projects that he is in and they aren't archived.
        $projects = $this->level == UserLevel::CLIENT ? $this->getAllUserProjects()->whereNull('archived_at') : ($this->level == UserLevel::WORKER ? $this->getAllUserProjects() : Project::all());


        $commentsCount = 0;
        foreach ($projects as $project) {
            $commentsCount += $project->unreadCommentsCount($this->id);
        }
        return $commentsCount;
    }
}
