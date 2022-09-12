<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ReportController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        date_default_timezone_set(get_option('timezone', 'Asia/Dhaka'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function transactions_report(Request $request) {
        if ($request->isMethod('get')) {
            return view('backend.customer_portal.reports.all_transactions');
        } else if ($request->isMethod('post')) {
            @ini_set('max_execution_time', 0);
            @set_time_limit(0);

            $data             = array();
            $date1            = $request->date1;
            $date2            = $request->date2;
            $status           = isset($request->status) ? $request->status : '';
            $transaction_type = isset($request->transaction_type) ? $request->transaction_type : '';

            $data['report_data'] = Transaction::select('transactions.*')
                ->when($status, function ($query, $status) {
                    return $query->where('status', $status);
                })
                ->when($transaction_type, function ($query, $transaction_type) {
                    return $query->where('type', $transaction_type);
                })
                ->with('currency')
                ->where('user_id', auth()->id())
                ->whereRaw("date(transactions.created_at) >= '$date1' AND date(transactions.created_at) <= '$date2'")
                ->orderBy('id', 'desc')
                ->get();

            $data['date1']          = $request->date1;
            $data['date2']          = $request->date2;
            $data['payment_status'] = $request->payment_status;
            $data['customer_email'] = $request->customer_email;
            return view('backend.customer_portal.reports.all_transactions', $data);
        }

    }

}