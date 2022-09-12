<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanPayment extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'loan_payments';

    public function loan() {
        return $this->belongsTo('App\Models\Loan', 'loan_id')->withDefault();
    }

    public function getPaidAtAttribute($value) {
        $date_format = get_date_format();
        return \Carbon\Carbon::parse($value)->format("$date_format");
    }
}