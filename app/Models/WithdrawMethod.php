<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawMethod extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'withdraw_methods';

    public function currency() {
        return $this->belongsTo('App\Models\Currency', 'currency_id')->withDefault();
    }

    public function getRequirementsAttribute($value) {
        return json_decode($value);
    }
}