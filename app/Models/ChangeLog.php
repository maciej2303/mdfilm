<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'authorable_type',
        'authorable_id',
        'change',
        'content',
        'relationable_id',
        'relationable_type',
        'model_type',
        'model_id',
    ];

    protected $with = ['authorable', 'relationable'];

    public function authorable()
    {
        return $this->morphTo();
    }

    public function relationable()
    {
        return $this->morphTo();
    }

    public function model()
    {
        return $this->morphTo();
    }
}
