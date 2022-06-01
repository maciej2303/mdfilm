<?php

namespace App\Services\Utils;

use Exception;
use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TranslationService
{
    public $tableName;
    public $modelId;


    public function __construct(string $tableName, int $modelId = null, $connection = 'mysql')
    {
        $this->tableName = $tableName;
        $this->modelId = $modelId;
        $this->connection = $connection;
    }


    public function saveForFields(array $fieldNames, Request $request)
    {
        foreach($fieldNames as $fieldName) {
            $translationArray = $request->input($fieldName, false);
            if($translationArray) {
                $this->saveTranslations(
                    $fieldName, $translationArray
                );
            }
        }
    }


    public function saveTranslations($fieldName, $translationArray) {
        foreach ($translationArray as $languageId => $value) {
            $translation = Translation::firstOrNew([
                'table' => $this->tableName,
                'model_id' => $this->modelId,
                'language_id' => $languageId,
                'field_name' => $fieldName,
            ]);
            $translation->localized_value = $value;
            $translation->save();
        }
    }


    public function getArray()
    {
        $translations_ = Translation::on($this->connection)->whereTable($this->tableName);
        if(!empty($this->modelId))
        {
            $translations_ =$translations_->whereModelId($this->modelId);
        }
        $translations_ = $translations_->get();
        $translations = array();
        foreach($translations_ as $key=>$data)
        {
            $translations[$data->model_id][$data->field_name][$data->language_id] = $data->localized_value;
        }
        return $translations;
    }
    public function getforVueArray()
    {
        $translations_ = Translation::on($this->connection)->where('language_id', \App::getLocale())->whereTable($this->tableName);
        if(!empty($this->modelId))
        {
            $translations_ =$translations_->whereModelId($this->modelId);
        }
        $translations_ = $translations_->get();
        $translations = array();
        
        foreach($translations_ as $key => $data)
        {
            $translations[$data->model_id] = $data->localized_value;
        }
        return $translations;
    }

    public function getArrayForModel()
    {
        if(is_null($this->modelId))
            throw new Exception("Model ID for getArrayForModel method cannot be null");
        $translations = $this->getArray();
        return @$translations[$this->modelId];
    }


    public function destroyTranslationsForIds(array $modelIdList = [])
    {
        Translation::where('table', $this->tableName)
            ->whereIn('model_id', $modelIdList)
            ->delete();

        return true;
    }


    public function destroyTranslationsForModel()
    {
        if(is_null($this->modelId))
            throw new Exception("Model ID for destroyTranslationsForModel method cannot be null");

        $this->destroyTranslationsForIds([$this->modelId]);

        return true;
    }


    /**
     * Returns a model basing on table name
     *
     * @param $table
     * @return string
     */
    public function getModel()
    {
        $model_name = Str::singular(Str::studly($this->tableName));
        $model = 'App\\'.$model_name;
        return $model;
    }


    /**
     * Delete translations that do not have any model related
     */
    public function clearOrphans()
    {
        $model = $this->getModel();
        $modelIdList = $model::select('id')->pluck('id');
        Translation::where('table', $this->tableName)
            ->whereNotIn('model_id', $modelIdList)
            ->delete();

        return $this;
    }

    public function getTranslatedModelValue(int $modelId, string $langCode = 'en'): ?string
    {
        $translation = Translation::select('localized_value')->where('table', $this->tableName)
            ->where('model_id', $modelId)
            ->where('language_id', $langCode)
            ->first();

        if($translation) {
            return $translation->localized_value;
        }
        return '';
    }

    public function getTranslationsForManyModelIDs(array $modelIDs)
    {
       $translations = Translation::where('table', $this->tableName)
            ->whereIn('model_id', $modelIDs)
            ->get()
            ->sortBy('id');

        $translationsArray = [];

        foreach($translations as $translation)
        {
           $translationsArray[$translation->model_id][$translation->field_name][$translation->language_id] = $translation->localized_value;
        }
        return $translationsArray;
    }
    public static function getJson() 
    {
        $lang =   \App::getLocale();
        $path = resource_path("lang/".$lang.".json");
        if (is_string($path) && is_readable($path)) {
            return file_get_contents($path);
        }
        return false;;
    }


}