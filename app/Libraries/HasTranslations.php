<?php

namespace App\Libraries;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;

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
                if ($this->hasAttribute($key) && $this->attributes[$key] != null && $key != 'id') {
                    $this->setAttribute($key, $value);
                }
            }
        }
        return $this;
    }

    public function addTranslation(array $attributes, int $language_id): void {
        $existingTranslation = $this->translations()->where('language_id', $language_id)->first();
        $existingTranslation?->delete();
        $attributes['language_id'] = $language_id;
        $this->translations()->create($attributes);
    }

    public function getTranslatableKeys(): array {
        $myColumns = Schema::getColumnListing($this->getTable());
        $myAttributes = [];
        foreach ($myColumns as $column) {
            $myAttributes[$column] = null;
        }
        $myTranslatableAttributes = Arr::only($myAttributes,[
            'first_name',
            'last_name',
            'job_description',
            'about',
            'address',
            'title',
            'description',
            'testimony',
            'testimony_name',
            'testimony_job_description',
            'testimony_country',
            'degree',
            'acknowledgement',
            'institute',
            'institute_country',
            'company_name',
            'company_country',
            'issuer',
            'name'
        ]);
        return array_keys($myTranslatableAttributes);
    }
}
