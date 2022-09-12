<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('currency')->insert([
            [
                'name'          => 'USD',
                'exchange_rate' => 1.000000,
                'base_currency' => 1,
                'status'        => 1,
            ],
            [
                'name'          => 'EUR',
                'exchange_rate' => 0.850000,
                'base_currency' => 0,
                'status'        => 1,
            ],
            [
                'name'          => 'USD',
                'exchange_rate' => 74.500000,
                'base_currency' => 0,
                'status'        => 1,
            ],
        ]);
    }
}
