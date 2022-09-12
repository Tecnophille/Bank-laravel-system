<?php

namespace App\Http\Controllers\Gateway\Flutterwave;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Notifications\DepositMoney;
use Illuminate\Http\Request;

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

        if (!isset($request->transaction_id)) {
            return redirect()->route('deposit.automatic_methods')->with('error', _lang('Sorry, Payment not completed !'));
        }

        $transaction = Transaction::find($request->deposit_id);

        $secret_key = $transaction->gateway->parameters->secret_key;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL            => "https://api.flutterwave.com/v3/transactions/" . $request->transaction_id . "/verify",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "GET",
            CURLOPT_HTTPHEADER     => array(
                "Content-Type: application/json",
                "Authorization: Bearer $secret_key",
            ),
        ));

        $response = json_decode(curl_exec($curl));

        curl_close($curl);

        if ($response->status == 'success') {

            $amount           = $response->data->amount;
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