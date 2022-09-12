<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FixedDeposit extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fdrs';

    public function plan() {
        return $this->belongsTo('App\Models\FDRPlan', 'fdr_plan_id')->withDefault();
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id')->withDefault();
    }

    public function currency() {
        return $this->belongsTo('App\Models\Currency', 'currency_id')->withDefault();
    }

    public function loan_product() {
        return $this->belongsTo('App\Models\FDRPlan', 'fdr_plan_id')->withDefault();
    }

    public function approved_by() {
        return $this->belongsTo('App\Models\User', 'approved_user_id')->withDefault();
    }

    public function created_by() {
        return $this->belongsTo('App\Models\User', 'created_user_id')->withDefault();
    }

    public function updated_by() {
        return $this->belongsTo('App\Models\User', 'updated_user_id')->withDefault();
    }

    public function transaction() {
        return $this->belongsTo('App\Models\Transaction', 'transaction_id')->withDefault();
    }

    public function getApprovedDateAttribute($value) {
        if ($value != null) {
            $date_format = get_date_format();
            return \Carbon\Carbon::parse($value)->format("$date_format");
        }
    }

    public function getMatureDateAttribute($value) {
        if ($value != null) {
            $date_format = get_date_format();
            return \Carbon\Carbon::parse($value)->format("$date_format");
        }
    }
}