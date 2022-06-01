<?php


namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class TranslationCollection extends Collection
{
    public function localizeAttr(string $attr, string $langCode, string $default = '')
    {
        return $this->where('language_id', $langCode)->field($attr, $default);
    }

    public function field(string $word, string $default = ''): string
    {
        $index = $this->search(function ($item, $key) use ($word) {
            return $item->field_name == $word;
        });

        return @$this[$index] ? $this[$index]->localized_value : $default;
    }
}