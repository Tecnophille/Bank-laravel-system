<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepositRequest extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'deposit_requests';

    public function method() {
        return $this->belongsTo('App\Models\DepositMethod', 'method_id')->withDefault();
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id')->withDefault();
    }

    public function getRequirementsAttribute($value) {
        return json_decode($value);
    }
}