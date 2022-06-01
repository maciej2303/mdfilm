<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Services\Utils\TranslationService;
use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'colour',
        'order',
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }
        public function getNameAttribute()
    {
        $lang = \App::getLocale();
        $translationService = (new TranslationService('event_types'));

        if($this->id) {
            return $translationService->getTranslatedModelValue($this->id, $lang);
        }
    }
}
