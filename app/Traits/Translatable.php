<?php

namespace App\Traits;

trait Translatable {

    public function initializeEducationTrait() {
        $this->with[] = 'translations';
    }
    /**
     * The "booting" method of the trait.
     *
     * @return void
     */
    public static function bootTranslatable() {
        static::saved(function ($entity) {
            $entity->saveTranslations(request('trans', []));
        });
    }

    public function translation() {
        return $this->hasOne($this->getTranslationModel(), $this->getForeignKey())
            ->where('locale', $this->localeOrFallback())
            ->withDefault();
    }

    public function translations() {
        return $this->hasMany($this->getTranslationModel(), $this->getForeignKey());
    }

    /**
     * Save Translation data for the entity.
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function saveTranslations($requestData = []) {
        $data = [];
        foreach ($requestData as $key => $value) {
            $data[$key] = xss_clean($value);
        }

        if ($this->translations->firstWhere('locale', get_language())) {
            $this->translation->fill($data);
            $this->translation->setAttribute('locale', get_language())->save();
        } else {
            $modelName = $this->getTranslationModel();

            $translation = new $modelName();
            $translation->fill($data);
            $translation->setAttribute($this->getForeignKey(), $this->getKey());
            $translation->setAttribute('locale', get_language())->save();
        }

    }

    public function getTranslationModel(): string {
        $modelName = get_class($this) . 'Translation';
        return $modelName;
    }

    private function localeOrFallback() {
        return $this->translations()->where('locale', get_language())->exists() ? get_language() : config('app.fallback_language');
    }

}
