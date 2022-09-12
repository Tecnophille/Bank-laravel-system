<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payment_gateways';

    public function getParametersAttribute($value) {
        return json_decode($value);
    }

    public function getSupportedCurrenciesAttribute($value) {
        return json_decode($value);
    }
}