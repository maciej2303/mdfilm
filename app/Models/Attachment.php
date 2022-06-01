<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'label',
        'url',
        'extension',
        'relationable_type',
        'relationable_id',
        'authorable_type',
        'authorable_id',
        'pinned'
    ];

    protected $with = ["authorable"];

    public function relationable()
    {
        return $this->morphTo();
    }

    public function authorable()
    {
        return $this->morphTo();
    }
}
