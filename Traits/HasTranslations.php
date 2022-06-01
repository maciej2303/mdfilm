<?php


namespace App\Traits;


use App\Models\Translation;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasTranslations
{
    /**
     * @return HasMany
     */
    public function translations(): HasMany
    {
        return $this->hasMany(Translation::class, 'model_id', 'id')
            ->where('table', $this->getTable());
    }
}