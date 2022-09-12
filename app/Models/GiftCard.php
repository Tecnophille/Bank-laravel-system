<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiftCard extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gift_cards';

    public function currency() {
        return $this->belongsTo('App\Models\Currency', 'currency_id')->withDefault();
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id')->withDefault();
    }

    public function getusedAtAttribute($value) {
        if ($value != '') {
            $date_format = get_date_format();
            $time_format = get_time_format();
            return \Carbon\Carbon::parse($value)->format("$date_format $time_format");
        }
    }
}