<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payment_requests';

    public function getCreatedAtAttribute($value) {
        $date_format = get_date_format();
        $time_format = get_time_format();
        return \Carbon\Carbon::parse($value)->format("$date_format $time_format");
    }

    public function currency() {
        return $this->belongsTo('App\Models\Currency', 'currency_id')->withDefault();
    }

    public function sender() {
        return $this->belongsTo('App\Models\User', 'sender_id')->withDefault();
    }

    public function receiver() {
        return $this->belongsTo('App\Models\User', 'receiver_id')->withDefault();
    }
}