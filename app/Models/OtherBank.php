<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtherBank extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'other_banks';

    public function currency() {
        return $this->belongsTo('App\Models\Currency', 'bank_currency')->withDefault();
    }
}