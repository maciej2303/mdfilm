<?php

namespace App\Models;

use App\Collections\TranslationCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    public $fillable = [
        'table',
        'model_id',
        'language_id',
        'field_name',
        'localized_value',
    ];

    /**
     * Create a new Eloquent Collection instance.
     * @param  array  $models
     * @return Collection
     */
    public function newCollection(array $models = []): Collection
    {
        return (new TranslationCollection($models));
    }
}
