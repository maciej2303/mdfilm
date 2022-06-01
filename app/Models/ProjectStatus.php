<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\Utils\TranslationService;

class ProjectStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'colour',
        'order',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class)->whereNull('archived_at')->orderBy('order', 'asc');
    }
    public function translation()
    {
        $lang = \App::getLocale();

        return $this->hasOne(Translation::class, 'model_id', 'id')
            ->where('table', 'project_statuses')
            ->where('language_id', $lang);
    }
    public function getNameAttribute()
    {
        $lang = \App::getLocale();
        $translationService = (new TranslationService('project_statuses'));

        if($this->id) {
            return $translationService->getTranslatedModelValue($this->id, $lang);
        }
    }
    public static function getSortedStauses()
    {
        $lang = \App::getLocale();
        $order =  self::select('project_statuses.id')->leftJoin("translations", function ($query) use ($lang) {
                $query->on("project_statuses.id", "=", "translations.model_id")
                    ->where("translations.table", "=", \DB::raw("'project_statuses'"))
                    ->where("translations.field_name", "=", \DB::raw("'name'"))
                    ->where("translations.language_id", \DB::raw("'{$lang}'"));
            })->orderBy("translations.localized_value", 'ASC')->get()->pluck('id')->toArray();

        return self::get()->sortBy(function($model) use ($order){
            return array_search($model->getKey(), $order);
        });
    }

    public static function getForVue() 
    {
        $lang = \App::getLocale();
        return self::leftJoin("translations", function ($query) use ($lang) {
                $query->on("project_statuses.id", "=", "translations.model_id")
                    ->where("translations.table", "=", \DB::raw("'project_statuses'"))
                    ->where("translations.field_name", "=", \DB::raw("'name'"))
                    ->where("translations.language_id", \DB::raw("'{$lang}'"));
            })->orderBy("translations.localized_value", 'ASC')->get();
    }

}
