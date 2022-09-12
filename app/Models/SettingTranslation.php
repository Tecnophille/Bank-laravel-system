<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingTranslation extends Model {

    protected $fillable = ['value'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'setting_translations';

    protected static function booted() {
        static::creating(function ($translation) {
            $translation->locale = get_language();
        });
    }
}