<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawRequest extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'withdraw_requests';

    public function method() {
        return $this->belongsTo('App\Models\WithdrawMethod', 'method_id')->withDefault();
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id')->withDefault();
    }

    public function getRequirementsAttribute($value) {
        return json_decode($value);
    }
}