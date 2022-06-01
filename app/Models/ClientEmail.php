<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'type',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
