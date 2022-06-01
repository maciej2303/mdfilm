<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\Utils\TranslationService;

class WorkTimeType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'colour',
        'order',
    ];
    public function translation()
    {
        $lang = \App::getLocale();

        return $this->hasOne(Translation::class, 'model_id', 'id')
            ->where('table', 'work_time_type')
            ->where('language_id', $lang);
    }
    public function getNameAttribute()
    {
        $lang = \App::getLocale();
        $translationService = (new TranslationService('work_time_types'));

        if($this->id) {
            return $translationService->getTranslatedModelValue($this->id, $lang);
        }
    }
    public static function getForVue() 
    {
        $lang = \App::getLocale();
        return self::leftJoin("translations", function ($query) use ($lang) {
                $query->on("work_time_types.id", "=", "translations.model_id")
                    ->where("translations.table", "=", \DB::raw("'work_time_types'"))
                    ->where("translations.field_name", "=", \DB::raw("'name'"))
                    ->where("translations.language_id", \DB::raw("'{$lang}'"));
            })->orderBy('work_time_types.order', 'asc')->get();
    }
}
