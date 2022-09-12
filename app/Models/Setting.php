<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'settings';

    public function translation() {
        return $this->hasOne('App\Models\SettingTranslation', 'setting_id')
            ->where('locale', get_language())
            ->withDefault(['value' => $this->value]);
    }
}