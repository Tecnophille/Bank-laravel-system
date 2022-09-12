<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\OtherBank;
use App\Models\Transaction;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransferController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        date_default_timezone_set(get_option('timezone', 'Asia/Dhaka'));
    }

    public function send_money(Request $request) {
        if ($request->isMethod('get')) {
            $alert_col = 'col-lg-8 offset-lg-2';
            return view('backend.customer_portal.send_money', compact('alert_col'));
        } else if ($request->isMethod('post')) {
            @ini_set('max_execution_time', 0);
            @set_time_limit(0);

            $validator = Validator::make($request->all(), [
                'user_account' => 'required',
                'currency_id'  => 'required',
                'amount'       => 'required|numeric|min:1.00',
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
                } else {
                    return back()
                        ->withErrors($validator)
                        ->withInput();
                }
            }

            $user = User::where('email', $request->user_account)
                ->where('user_type', 'customer')
                ->where('id', '!=', auth()->id())
                ->first();

            if (!$user) {
                return back()->with('error', _lang('User Account not found !'))->withInput();
            }

            $charge = 0;

            if (get_option('transfer_fee_type') == 'percentage') {
                $charge = (get_option('transfer_fee', 0) / 100) * $request->amount;
            } else if (get_option('transfer_fee_type') == 'fixed') {
                $charge = convert_currency(base_currency_id(), $request->currency_id, get_option('transfer_fee', 0));
            }

            //Check Available Balance
            if (get_account_balance($request->currency_id) < $request->amount + $charge) {
                if (!$request->ajax()) {
                    return back()->with('error', _lang('Insufficient balance !'))->withInput();
                } else {
                    return response()->json(['result' => 'error', 'message' => _lang('Insufficient balance !')]);
                }
            }

            DB::beginTransaction();

            //Create Debit Transactions
            $debit                  = new Transaction();
            $debit->user_id         = auth()->id();
            $debit->currency_id     = $request->input('currency_id');
            $debit->amount          = $request->input('amount') + $charge;
            $debit->fee             = $charge;
            $debit->dr_cr           = 'dr';
            $debit->type            = 'Transfer';
            $debit->method          = 'Online';
            $debit->status          = 2;
            $debit->note            = $request->input('note');
            $debit->created_user_id = auth()->id();
            $debit->branch_id       = auth()->user()->branch_id;

            $debit->save();

            //Create Credit Transactions
            $credit                  = new Transaction();
            $credit->user_id         = $user->id;
            $credit->currency_id     = $request->input('currency_id');
            $credit->amount          = $request->input('amount');
            $credit->dr_cr           = 'cr';
            $credit->type            = 'Transfer';
            $credit->method          = 'Online';
            $credit->status          = 2;
            $credit->note            = $request->input('note');
            $credit->created_user_id = auth()->id();
            $credit->branch_id       = auth()->user()->branch_id;
            $credit->parent_id       = $debit->id;

            $credit->save();

            DB::commit();

            if (!$request->ajax()) {
                return redirect()->route('transfer.send_money')->with('success', _lang('Money Transfered Successfully'));
            } else {
                return response()->json(['result' => 'success', 'action' => 'store', 'message' => _lang('Money Transfered Successfully'), 'data' => $transaction, 'table' => '#transactions_table']);
            }
        }

    }

    public function exchange_money(Request $request) {
        if ($request->isMethod('get')) {
            $alert_col = 'col-lg-8 offset-lg-2';
            return view('backend.customer_portal.exchange_money', compact('alert_col'));
        } else if ($request->isMethod('post')) {
            @ini_set('max_execution_time', 0);
            @set_time_limit(0);

            $validator = Validator::make($request->all(), [
                'currency_from' => 'required',
                'currency_to'   => 'required',
                'amount'        => 'required|numeric|min:1.00',
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
                } else {
                    return back()
                        ->withErrors($validator)
                        ->withInput();
                }
            }

            $charge = 0;

            if (get_option('exchange_fee_type') == 'percentage') {
                $charge = (get_option('exchange_fee', 0) / 100) * $request->amount;
            } else if (get_option('exchange_fee_type') == 'fixed') {
                $charge = convert_currency(base_currency_id(), $request->currency_from, get_option('exchange_fee', 0));
            }

            //Check Available Balance
            if (get_account_balance($request->currency_from) < $request->amount + $charge) {
                if (!$request->ajax()) {
                    return back()->with('error', _lang('Insufficient balance !'))->withInput();
                } else {
                    return response()->json(['result' => 'error', 'message' => _lang('Insufficient balance !')]);
                }
            }

            DB::beginTransaction();

            //Create Debit Transactions
            $debit                  = new Transaction();
            $debit->user_id         = auth()->id();
            $debit->currency_id     = $request->input('currency_from');
            $debit->amount          = $request->input('amount') + $charge;
            $debit->fee             = $charge;
            $debit->dr_cr           = 'dr';
            $debit->type            = 'Exchange';
            $debit->method          = 'Online';
            $debit->status          = 2;
            $debit->note            = $request->input('note');
            $debit->created_user_id = auth()->id();
            $debit->branch_id       = auth()->user()->branch_id;

            $debit->save();

            //Create Credit Transactions
            $credit                  = new Transaction();
            $credit->user_id         = auth()->id();
            $credit->currency_id     = $request->currency_to;
            $credit->amount          = convert_currency($request->currency_from, $request->currency_to, $request->amount);
            $credit->dr_cr           = 'cr';
            $credit->type            = 'Exchange';
            $credit->method          = 'Online';
            $credit->status          = 2;
            $credit->note            = $request->input('note');
            $credit->created_user_id = auth()->id();
            $credit->branch_id       = auth()->user()->branch_id;
            $credit->parent_id       = $debit->id;

            $credit->save();

            DB::commit();

            if (!$request->ajax()) {
                return redirect()->route('transfer.exchange_money')->with('success', _lang('Money Exchanged Successfully'));
            } else {
                return response()->json(['result' => 'success', 'action' => 'store', 'message' => _lang('Money Exchanged Successfully'), 'data' => $transaction, 'table' => '#transactions_table']);
            }
        }

    }

    public function wire_transfer(Request $request) {
        if ($request->isMethod('get')) {
            $alert_col = 'col-lg-8 offset-lg-2';
            return view('backend.customer_portal.wire_transfer', compact('alert_col'));
        } else if ($request->isMethod('post')) {
            @ini_set('max_execution_time', 0);
            @set_time_limit(0);

            $validator = Validator::make($request->all(), [
                'bank'                   => 'required',
                'amount'                 => 'required|numeric',
                'td.account_number'      => 'required',
                'td.account_holder_name' => 'required',
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
                } else {
                    return back()
                        ->withErrors($validator)
                        ->withInput();
                }
            }

            $bank = OtherBank::find($request->bank);

            $charge = $bank->fixed_charge;
            $charge += ($bank->charge_in_percentage / 100) * $request->amount;

            //Check Minimum & Maximum Amount
            if ($request->amount < $bank->minimum_transfer_amount || $request->amount > $bank->maximum_transfer_amount) {
                return back()->with('error', _lang('Amount must be') . ' (' . $bank->currency->name . ' ' . $bank->minimum_transfer_amount . ' - ' . $bank->currency->name . ' ' . $bank->maximum_transfer_amount . ')')->withInput();
            }

            //Check Available Balance
            if (get_account_balance($bank->bank_currency) < $request->amount + $charge) {
                return back()->with('error', _lang('Insufficient balance !'))->withInput();
            }

            //Create Debit Transactions
            $debit                      = new Transaction();
            $debit->user_id             = auth()->id();
            $debit->currency_id         = $bank->bank_currency;
            $debit->amount              = $request->input('amount') + $charge;
            $debit->fee                 = $charge;
            $debit->dr_cr               = 'dr';
            $debit->type                = 'Wire_Transfer';
            $debit->method              = 'Manual';
            $debit->status              = 1;
            $debit->note                = $request->input('note');
            $debit->other_bank_id       = $bank->id;
            $debit->created_user_id     = auth()->id();
            $debit->branch_id           = auth()->user()->branch_id;
            $debit->transaction_details = json_encode($request->td);

            $debit->save();

            return redirect()->route('transfer.wire_transfer')->with('success', _lang('Your Transfer Request send sucessfully. You will notified after reviewing by authority.'));

        }

    }

    public function get_other_bank_details($id) {
        $bank = \App\Models\OtherBank::with('currency')->find($id);
        return response()->json($bank);
    }

    public function show_transaction($id) {
        if (request()->ajax()) {
            $transaction = \App\Models\Transaction::where('id', $id)->where('user_id', auth()->id())->first();
            return view('backend.customer_portal.transaction_details', compact('transaction'));
        }
        return back();
    }

}