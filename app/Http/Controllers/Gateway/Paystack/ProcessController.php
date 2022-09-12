<?php

namespace App\Http\Controllers\Gateway\Paystack;

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

        $transaction = Transaction::find($request->deposit_id);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL            => "https://api.paystack.co/transaction/verify/" . $request->reference,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "GET",
            CURLOPT_HTTPHEADER     => array(
                "Authorization: Bearer " . $transaction->gateway->parameters->paystack_secret_key,
                "Cache-Control: no-cache",
            ),
        ));

        $response = curl_exec($curl);
        $err      = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return redirect()->route('deposit.automatic_methods')->with('error', $err);
        }

        $charge = json_decode($response);

        if ($charge->status == true) {

            $amount           = $charge->data->amount / 100;
            $converted_amount = convert_currency_2($transaction->gateway->exchange_rate, 1, $amount);

            //Update Transaction
            if (round($transaction->amount + $transaction->fee) >= round($converted_amount)) {
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