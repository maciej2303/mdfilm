<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'start',
        'end',
        'start_time',
        'end_time',
        'event',
        'description',
        'event_type_id',
        'project_id',
    ];

    protected $appends = [
        'hours'
    ];

    protected $with = ['members'];

    public function getStartTimeAttribute()
    {
        $startTime = $this->attributes['start_time'] != null ? Carbon::parse($this->attributes['start_time'])->format('H:i') : null;
        return $startTime;
    }

    public function getEndTimeAttribute()
    {
        $endTime = $this->attributes['end_time'] != null ?  Carbon::parse($this->attributes['end_time'])->format('H:i') : null;
        return $endTime;
    }

    public function getHoursAttribute()
    {
        $hours = '';
        if ($this->attributes['start_time'] != null && $this->attributes['end_time'] != null) {
            $hours = $this->start_time . " - " . $this->end_time;
        }
        return $hours;
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'event_member', 'event_id', 'user_id');
    }

    public function eventType()
    {
        return $this->belongsTo(EventType::class);
    }
}
