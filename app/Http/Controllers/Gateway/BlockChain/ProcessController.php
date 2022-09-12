<?php

namespace App\Http\Controllers\Gateway\BlockChain;

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
        $data = array();

        $data['custom'] = $deposit->id;
        $data['view']   = 'backend.customer_portal.gateway.' . $deposit->gateway->slug;

        //Convert USD to to BTC Amount
        $amount                   = $deposit->amount + $deposit->fee;
        $btc_amount               = file_get_contents("https://blockchain.info/tobtc?currency=USD&value={$amount}");
        $data['converted_amount'] = $btc_amount;

        //Block Chain Implementation
        $secret = "KJSHY$#16ao2dsf7hg65dssA";

        $xpub    = $deposit->gateway->parameters->blockchain_xpub;
        $api_key = $deposit->gateway->parameters->blockchain_api_key;

        $data['callback_url'] = route('callback.' . $deposit->gateway->slug) . "?invoice_id=" . $deposit->id . "&secret=" . $secret;
        $root_url             = 'https://api.blockchain.info/v2/receive';

        $parameters = "key=" . $api_key . "&callback=" . urlencode($data['callback_url']) . "&xpub=" . $xpub;

        $resp = @file_get_contents($root_url . '?' . $parameters);

        if (!$resp) {
            $data['error']         = true;
            $data['error_message'] = _lang('BlockChain API having issue. Please use valid API KEY and XPUB !');
            return json_encode($data);
        }

        $response = json_decode($resp);

        $transaction_details = array(
            'btc_address' => $response->address,
            'btc_amount'  => $btc_amount,
        );

        $deposit->transaction_details = json_encode($transaction_details);
        $deposit->save();

        $qr_code             = "bitcoin:" . $response->address . "?amount=" . $data['converted_amount'];
        $data['btc_address'] = $response->address;
        $data['qr_code']     = "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$qr_code&choe=UTF-";

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

        $invoice_id    = $_GET['invoice_id'];
        $secret        = $_GET['secret'];
        $address       = $_GET['address'];
        $value         = $_GET['value'];
        $confirmations = $_GET['confirmations'];
        $value_in_btc  = $value / 100000000;

        $my_secret = "KJSHY$#16ao2dsf7hg65dssA";

        $trx_hash = $_GET['transaction_hash'];

        $transaction = Transaction::find($invoice_id);

        if ($value_in_btc >= $transaction->transaction_details->btc_amount && $address == $transaction->transaction_details->btc_address && $secret == $my_secret && $confirmations > 2) {

            //Update Transaction
            $transaction->status = 2; // Completed
            $transaction->save();

            //Trigger Deposit Money notifications
            try {
                $transaction->user->notify(new DepositMoney($transaction));
            } catch (\Exception $e) {}
        }
    }

}