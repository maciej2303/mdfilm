<?php

namespace App\Models;

use App\Enums\ClientEmailTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nip',
        'address',
        'contact_person',
        'additional_informations',
        'phone_number',
        'status',
        'logo',
        'who_add',
    ];


    public function getImplodedBillingEmailsAttribute()
    {
        return implode(';', $this->billingEmails()->pluck('email')->toArray());
    }

    public function getImplodedContactEmailsAttribute()
    {
        return implode(';', $this->contactEmails()->pluck('email')->toArray());
    }

    public function scopeWithAndWhereHas($query, $relation, $constraint)
    {
        return $query->whereHas($relation, $constraint)
            ->with([$relation => $constraint]);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class)->orderBy('clients_order', 'asc');
    }

    public function emails()
    {
        return $this->hasMany(ClientEmail::class);
    }

    public function billingEmails()
    {
        return $this->emails()->where('type', ClientEmailTypes::BILLING);
    }

    public function contactEmails()
    {
        return $this->emails()->where('type', ClientEmailTypes::CONTACT);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
