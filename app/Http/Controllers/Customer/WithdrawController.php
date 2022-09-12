<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\WithdrawMethod;
use App\Models\WithdrawRequest;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WithdrawController extends Controller {

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
    public function manual_methods() {
        $withdraw_methods = WithdrawMethod::where('status', 1)->get();
        return view('backend.customer_portal.withdraw.manual_methods', compact('withdraw_methods'));
    }

    public function manual_withdraw(Request $request, $methodId) {
        if ($request->isMethod('get')) {
            $withdraw_method = WithdrawMethod::find($methodId);
            return view('backend.customer_portal.withdraw.modal.manual_withdraw', compact('withdraw_method'));
        } else if ($request->isMethod('post')) {
            $withdraw_method = WithdrawMethod::find($methodId);

            $min_amount = $withdraw_method->minimum_amount;
            $max_amount = $withdraw_method->maximum_amount;

            $validator = Validator::make($request->all(), [
                'requirements.*' => 'required',
                'amount'         => "required|numeric|min:$min_amount|max:$max_amount",
                'attachment'     => 'nullable|mimes:jpeg,JPEG,png,PNG,jpg,doc,pdf,docx,zip',
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
                } else {
                    return redirect()->route('withdraw.manual_withdraw')
                        ->withErrors($validator)
                        ->withInput();
                }
            }

            $charge = $withdraw_method->fixed_charge;
            $charge += ($withdraw_method->charge_in_percentage / 100) * $request->amount;

            //Check Available Balance
            if (get_account_balance($withdraw_method->currency_id) < $request->amount + $charge) {
                if (!$request->ajax()) {
                    return back()->with('error', _lang('Insufficient balance !'))->withInput();
                } else {
                    return response()->json(['result' => 'error', 'message' => _lang('Insufficient balance !')]);
                }
            }

            $attachment = "";
            if ($request->hasfile('attachment')) {
                $file       = $request->file('attachment');
                $attachment = time() . $file->getClientOriginalName();
                $file->move(public_path() . "/uploads/media/", $attachment);
            }

            DB::beginTransaction();

            //Create Debit Transactions
            $debit                  = new Transaction();
            $debit->user_id         = auth()->id();
            $debit->currency_id     = $withdraw_method->currency_id;
            $debit->amount          = $request->input('amount') + $charge;
            $debit->fee             = $charge;
            $debit->dr_cr           = 'dr';
            $debit->type            = 'Withdraw';
            $debit->method          = 'Manual';
            $debit->status          = 1;
            $debit->created_user_id = auth()->id();
            $debit->branch_id       = auth()->user()->branch_id;

            $debit->save();

            $withdrawRequest                 = new WithdrawRequest();
            $withdrawRequest->user_id        = auth()->id();
            $withdrawRequest->method_id      = $methodId;
            $withdrawRequest->amount         = $request->amount;
            $withdrawRequest->description    = $request->description;
            $withdrawRequest->requirements   = json_encode($request->requirements);
            $withdrawRequest->attachment     = $attachment;
            $withdrawRequest->transaction_id = $debit->id;
            $withdrawRequest->save();

            DB::commit();

            if (!$request->ajax()) {
                return redirect()->route('withdraw.manual_withdraw')->with('success', _lang('Withdraw Request Submited'));
            } else {
                return response()->json(['result' => 'success', 'action' => 'store', 'message' => _lang('Withdraw Request Submited'), 'data' => $withdrawRequest, 'table' => '#unknown_table']);
            }

        }
    }

}