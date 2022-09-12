<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\FDRPlan;
use App\Models\FixedDeposit;
use App\Models\Transaction;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FixedDepositController extends Controller {

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
    public function index() {
        $fixedDeposits = FixedDeposit::select('fdrs.*')
            ->with('plan')
            ->with('currency')
            ->where('user_id', auth()->id())
            ->orderBy("fdrs.id", "desc")
            ->get();
        return view('backend.customer_portal.fixed_deposit.list', compact('fixedDeposits'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function apply(Request $request) {
        if ($request->isMethod('get')) {
            $alert_col = 'col-lg-8 offset-lg-2';
            return view('backend.customer_portal.fixed_deposit.apply', compact('alert_col'));
        } else {
            $fdrPlan    = FDRPlan::find($request->fdr_plan_id);
            $min_amount = $fdrPlan->minimum_amount;
            $max_amount = $fdrPlan->maximum_amount;

            $validator = Validator::make($request->all(), [
                'fdr_plan_id'    => 'required',
                'currency_id'    => 'required',
                'deposit_amount' => "required|numeric:min:$min_amount|max:$max_amount",
                'attachment'     => 'nullable|mimes:jpeg,JPEG,png,PNG,jpg,doc,pdf,docx,zip',
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
                } else {
                    return redirect()->route('fixed_deposits.apply')
                        ->withErrors($validator)
                        ->withInput();
                }
            }

            //Check Available Balance
            if (get_account_balance($request->currency_id) < $request->deposit_amount) {
                return back()->with('error', _lang('Insufficient balance !'))->withInput();
            }

            $attachment = '';
            if ($request->hasfile('attachment')) {
                $file       = $request->file('attachment');
                $attachment = time() . $file->getClientOriginalName();
                $file->move(public_path() . "/uploads/media/", $attachment);
            }

            DB::beginTransaction();

            //Create Debit Transactions
            $debit                  = new Transaction();
            $debit->user_id         = auth()->id();
            $debit->currency_id     = $request->currency_id;
            $debit->amount          = $request->input('deposit_amount');
            $debit->dr_cr           = 'dr';
            $debit->type            = 'Fixed_Deposit';
            $debit->method          = 'Online';
            $debit->status          = 1; //Pending
            $debit->created_user_id = auth()->id();
            $debit->branch_id       = auth()->user()->branch_id;

            $debit->save();

            $fixeddeposit                  = new FixedDeposit();
            $fixeddeposit->fdr_plan_id     = $request->input('fdr_plan_id');
            $fixeddeposit->user_id         = auth()->id();
            $fixeddeposit->currency_id     = $request->input('currency_id');
            $fixeddeposit->deposit_amount  = $request->input('deposit_amount');
            $fixeddeposit->return_amount   = $fixeddeposit->deposit_amount + (($fdrPlan->interest_rate / 100) * $fixeddeposit->deposit_amount);
            $fixeddeposit->mature_date     = date("Y-m-d", strtotime('+ ' . $fdrPlan->duration . ' ' . $fdrPlan->duration_type));
            $fixeddeposit->attachment      = $attachment;
            $fixeddeposit->remarks         = $request->input('remarks');
            $fixeddeposit->created_user_id = auth()->user()->id;
            $fixeddeposit->branch_id       = auth()->user()->branch_id;
            $fixeddeposit->transaction_id  = $debit->id;

            $fixeddeposit->save();

            DB::commit();

            return redirect()->route('fixed_deposits.history')->with('success', _lang('Your request has been submitted. You will be notified shortly after reviewing by authority.'));

        }
    }

}