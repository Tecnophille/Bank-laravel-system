<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class UtilitySeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        //Default Settings
        DB::table('settings')->insert([
            [
                'name'  => 'mail_type',
                'value' => 'smtp',
            ],
            [
                'name'  => 'backend_direction',
                'value' => 'ltr',
            ],
            [
                'name'  => 'language',
                'value' => 'English',
            ],
            [
                'name'  => 'email_verification',
                'value' => 'disabled',
            ],
            [
                'name'  => 'allow_singup',
                'value' => 'yes',
            ],
        ]);

        //Payment Gateways
        DB::table('payment_gateways')->insert([
            [
                'name'                 => 'PayPal',
                'slug'                 => 'PayPal',
                'image'                => 'paypal.png',
                'status'               => 0,
                'parameters'           => '{"client_id":"","client_secret":"","environment":"sandbox"}',
                'supported_currencies' => '{"AUD":"AUD","BRL":"BRL","CAD":"CAD","CZK":"CZK","DKK":"DKK","EUR":"EUR","HKD":"HKD","HUF":"HUF","INR":"INR","ILS":"ILS","JPY":"JPY","MYR":"MYR","MXN":"MXN","TWD":"TWD","NZD":"NZD","NOK":"NOK","PHP":"PHP","PLN":"PLN","GBP":"GBP","RUB":"RUB","SGD":"SGD","SEK":"SEK","CHF":"CHF","THB":"THB","USD":"USD"}',
            ],
            [
                'name'                 => 'Stripe',
                'slug'                 => 'Stripe',
                'image'                => 'stripe.png',
                'status'               => 0,
                'parameters'           => '{"secret_key":"","publishable_key":""}',
                'supported_currencies' => '{"USD":"USD","AUD":"AUD","BRL":"BRL","CAD":"CAD","CHF":"CHF","DKK":"DKK","EUR":"EUR","GBP":"GBP","HKD":"HKD","INR":"INR","JPY":"JPY","MXN":"MXN","MYR":"MYR","NOK":"NOK","NZD":"NZD","PLN":"PLN","SEK":"SEK","SGD":"SGD"}',
            ],
            [
                'name'                 => 'Razorpay',
                'slug'                 => 'Razorpay',
                'image'                => 'razorpay.png',
                'status'               => 0,
                'parameters'           => '{"razorpay_key_id":"","razorpay_key_secret":""}',
                'supported_currencies' => '{"INR":"INR"}',
            ],
            [
                'name'                 => 'Paystack',
                'slug'                 => 'Paystack',
                'image'                => 'paystack.png',
                'status'               => 0,
                'parameters'           => '{"paystack_public_key":"","paystack_secret_key":""}',
                'supported_currencies' => '{"GHS":"GHS","NGN":"NGN","ZAR":"ZAR"}',
            ],
            [
                'name'                 => 'BlockChain',
                'slug'                 => 'BlockChain',
                'image'                => 'blockchain.png',
                'status'               => 0,
                'parameters'           => '{"blockchain_api_key":"","blockchain_xpub":""}',
                'supported_currencies' => '{"BTC":"BTC"}',
            ],
            [
                'name'                 => 'Flutterwave',
                'slug'                 => 'Flutterwave',
                'image'                => 'flutterwave.png',
                'status'               => 0,
                'parameters'           => '{"public_key":"","secret_key":"","encryption_key":"","environment":"sandbox"}',
                'supported_currencies' => '{"BIF":"BIF","CAD":"CAD","CDF":"CDF","CVE":"CVE","EUR":"EUR","GBP":"GBP","GHS":"GHS","GMD":"GMD","GNF":"GNF","KES":"KES","LRD":"LRD","MWK":"MWK","MZN":"MZN","NGN":"NGN","RWF":"RWF","SLL":"SLL","STD":"STD","TZS":"TZS","UGX":"UGX","USD":"USD","XAF":"XAF","XOF":"XOF","ZMK":"ZMK","ZMW":"ZMW","ZWD":"ZWD"}',
            ],
        ]);

    }
}
