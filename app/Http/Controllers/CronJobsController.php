<?php

namespace App\Http\Controllers;

use App\Models\FixedDeposit;
use App\Models\Transaction;
use App\Notifications\FDRMatured;
use DB;

class CronJobsController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        date_default_timezone_set(get_option('timezone', 'Asia/Dhaka'));
    }
    /**
     * Show the application CronJobs.
     *
     * @return \Illuminate\Http\Response
     */
    public function run() {
        @ini_set('max_execution_time', 0);
        @set_time_limit(0);

        DB::beginTransaction();

        $fixed_deposits = FixedDeposit::where('status', 1)
            ->where('mature_date', '<=', date('Y-m-d'))
            ->get();

        foreach ($fixed_deposits as $fixed_deposit) {
            $fixed_deposit->status = 3;
            $fixed_deposit->save();

            $transaction              = new Transaction();
            $transaction->user_id     = $fixed_deposit->user_id;
            $transaction->currency_id = $fixed_deposit->currency_id;
            $transaction->amount      = $fixed_deposit->return_amount;
            $transaction->dr_cr       = 'cr';
            $transaction->type        = 'Deposit';
            $transaction->method      = 'Online';
            $transaction->status      = 2;
            $transaction->note        = 'Return of Fixed deposit';

            $transaction->save();

            try {
                $transaction->user->notify(new FDRMatured($transaction));
            } catch (\Exception $e) {}
        }

        DB::commit();

        echo 'Scheduled task runs successfully';
    }

}
