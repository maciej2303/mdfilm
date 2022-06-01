<?php

namespace App\Models;

use App\Enums\UserLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectElementComponentVersion extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'inner',
        'version',
        'project_element_component_id',
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($projectElementComponentVersion) {
            $projectElementComponentVersion->comments()->delete();
            $projectElementComponentVersion->attachments()->delete();
            $projectElementComponentVersion->tasks()->delete();
            $projectElementComponentVersion->changeLogs()->delete();
        });
    }


    public function projectElementComponent()
    {
        return $this->belongsTo(ProjectElementComponent::class);
    }

    public function project()
    {
        return $this->projectElementComponent->projectElement->project;
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'relationable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'relationable');
    }

    public function isLastVersion() 
    {
        if($this->projectElementComponent->versions) {
            return $this->projectElementComponent->versions->last()->id == $this->id;
        }
        return false;
    }

    public function unreadCommentsCount($userId)
    {
        $user = User::find($userId);
        $readComments = $user->readComments->pluck('id');
        $commentsCount = 0;
        $comments = $user->level == UserLevel::CLIENT ? $this->externalComments->pluck('id') : $this->comments->pluck('id');
        foreach ($comments as $comment) {
            if (!$readComments->contains($comment)) {
                $commentsCount++;
            }
        }
        return $commentsCount;
    }

    public function externalComments()
    {
        return $this->comments()->where('inner', 0);
    }

    public function innerComments()
    {
        return $this->comments()->where('inner', 1);
    }
    public function allComments()
    {
        return $this->comments();
    }

    public function acceptances()
    {
        return $this->hasMany(ProjectElementComponentVersionAcceptance::class);
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
}
