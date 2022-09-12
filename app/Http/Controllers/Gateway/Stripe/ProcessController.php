<?php

namespace App\Http\Controllers\Gateway\Stripe;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Notifications\DepositMoney;
use Illuminate\Http\Request;
use Stripe;

class ProcessController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        ini_set('error_reporting', E_ALL);
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');

        date_default_timezone_set(get_option('timezone', 'Asia/Dhaka'));
    }

    /**
     * Process Payment Gateway
     *
     * @return \Illuminate\Http\Response
     */
    public static function process($deposit) {
        $data                 = array();
        $data['callback_url'] = route('callback.' . $deposit->gateway->slug);
        $data['custom']       = $deposit->id;
        $data['view']         = 'backend.customer_portal.gateway.' . $deposit->gateway->slug;

        return json_encode($data);
    }

    /**
     * Callback function from Payment Gateway
     *
     * @return \Illuminate\Http\Response
     */
    public function callback(Request $request) {
        @ini_set('max_execution_time', 0);
        @set_time_limit(0);

        $transaction = Transaction::find($request->deposit_id);

        Stripe\Stripe::setApiKey($transaction->gateway->parameters->secret_key);
        $charge = Stripe\Charge::create([
            "amount"      => convert_currency_2(1, $transaction->gateway->exchange_rate, round(($transaction->amount + $transaction->fee) * 100)),
            "currency"    => $transaction->gateway->currency,
            "source"      => $request->stripeToken,
            "description" => _lang('Deposit Via Stripe'),
        ]);

        if ($charge->amount_refunded == 0 && $charge->failure_code == null && $charge->paid == true && $charge->captured == true) {

            $amount           = $charge->amount / 100;
            $converted_amount = convert_currency_2($transaction->gateway->exchange_rate, 1, $amount);

            //Update Transaction
            if (($transaction->amount + $transaction->fee) >= $converted_amount) {
                $transaction->status = 2; // Completed
                $transaction->save();
            }

            //Trigger Deposit Money notifications
            try {
                $transaction->user->notify(new DepositMoney($transaction));
            } catch (\Exception $e) {}

            return redirect()->route('dashboard.index')->with('success', _lang('Money Deposited Successfully'));
        } else {
            return redirect()->route('deposit.automatic_methods')->with('error', _lang('Sorry, Payment not completed !'));
        }
    }

}