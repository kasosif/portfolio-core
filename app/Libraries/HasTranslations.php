<?php

namespace App\Libraries;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasTranslations {

    public function hasAttribute($attr): bool
    {
        return array_key_exists($attr, $this->attributes);
    }

    public function translations():MorphMany
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    public function translate(int $languageId): object | null {
        $translation = $this->translations()->where('language_id', $languageId)->first();
        if ($translation) {
            $translationAttributes = $translation->getAttributes();
            foreach ($translationAttributes as $key => $value) {
                if ($this->hasAttribute($key) && $this->attributes[$key] != null) {
                    $this->setAttribute($key, $value);
                }
            }
        }
        return $this;
    }

    public function addTranslation(array $attributes, int $language_id): void {
        $exists = $this->translations()->where('language_id', $language_id)->exists();
        if (!$exists) {
            $attributes['language_id'] = $language_id;
            $this->translations()->create($attributes);
        }
    }
}
