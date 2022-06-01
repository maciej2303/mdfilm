<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment',
        'authorable_type',
        'authorable_id',
        'inner',
        'relationable_type',
        'relationable_id',
        'label',
        'start_time',
        'end_time',
        'x',
        'y',
    ];

    protected $with = ['authorable'];

    public function authorable()
    {
        return $this->morphTo();
    }

    public function relationable()
    {
        return $this->morphTo();
    }

    public function userWhoRead()
    {
        return $this->belongsToMany(User::class, 'read_comments', 'comment_id', 'user_id');
    }
}
