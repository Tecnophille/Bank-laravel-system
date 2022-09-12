<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepositMethod extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'deposit_methods';

    public function currency() {
        return $this->belongsTo('App\Models\Currency', 'currency_id')->withDefault();
    }

    public function getRequirementsAttribute($value) {
        return json_decode($value);
    }
}