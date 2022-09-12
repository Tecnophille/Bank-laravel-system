<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transactions';

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id')->withDefault();
    }

    public function currency() {
        return $this->belongsTo('App\Models\Currency', 'currency_id')->withDefault();
    }

    public function other_bank() {
        return $this->belongsTo('App\Models\OtherBank', 'other_bank_id')->withDefault();
    }

    public function gateway() {
        return $this->belongsTo('App\Models\PaymentGateway', 'gateway_id')->withDefault();
    }

    public function created_by() {
        return $this->belongsTo('App\Models\User', 'created_user_id')->withDefault();
    }

    public function updated_by() {
        return $this->belongsTo('App\Models\User', 'updated_user_id')->withDefault();
    }

    public function getCreatedAtAttribute($value) {
        $date_format = get_date_format();
        $time_format = get_time_format();
        return \Carbon\Carbon::parse($value)->format("$date_format $time_format");
    }

    public function getTransactionDetailsAttribute($value) {
        return json_decode($value);
    }
}