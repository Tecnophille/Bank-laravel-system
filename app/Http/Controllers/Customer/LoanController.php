<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\LoanPayment;
use App\Models\Transaction;
use App\Utilities\LoanCalculator as Calculator;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoanController extends Controller {

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
        $loans = Loan::where('borrower_id', auth()->id())
            ->orderBy("loans.id", "desc")
            ->get();
        return view('backend.customer_portal.loan.my_loans', compact('loans'));
    }

    public function loan_details($loan_id) {
        $data = array();
        $loan = Loan::where('id', $loan_id)
            ->where('borrower_id', auth()->id())
            ->first();

        if ($loan) {
            return view('backend.customer_portal.loan.loan_details', compact('loan'));
        }
    }

    public function calculator(Request $request) {
        if ($request->isMethod('get')) {
            $data                           = array();
            $data['first_payment_date']     = '';
            $data['apply_amount']           = '';
            $data['interest_rate']          = '';
            $data['interest_type']          = '';
            $data['term']                   = '';
            $data['term_period']            = '';
            $data['late_payment_penalties'] = 0;
            return view('backend.customer_portal.loan.calculator', $data);
        } else if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'apply_amount'           => 'required|numeric',
                'interest_rate'          => 'required',
                'interest_type'          => 'required',
                'term'                   => 'required|integer|max:100',
                'term_period'            => $request->interest_type == 'one_time' ? '' : 'required',
                'late_payment_penalties' => 'required',
                'first_payment_date'     => 'required',
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
                } else {
                    return redirect()->route('loans.calculator')->withErrors($validator)->withInput();
                }
            }

            $first_payment_date     = $request->first_payment_date;
            $apply_amount           = $request->apply_amount;
            $interest_rate          = $request->interest_rate;
            $interest_type          = $request->interest_type;
            $term                   = $request->term;
            $term_period            = $request->term_period;
            $late_payment_penalties = $request->late_payment_penalties;

            $data       = array();
            $table_data = array();

            if ($interest_type == 'flat_rate') {

                $calculator             = new Calculator($apply_amount, $first_payment_date, $interest_rate, $term, $term_period, $late_payment_penalties);
                $table_data             = $calculator->get_flat_rate();
                $data['payable_amount'] = $calculator->payable_amount;

            } else if ($interest_type == 'fixed_rate') {

                $calculator             = new Calculator($apply_amount, $first_payment_date, $interest_rate, $term, $term_period, $late_payment_penalties);
                $table_data             = $calculator->get_fixed_rate();
                $data['payable_amount'] = $calculator->payable_amount;

            } else if ($interest_type == 'mortgage') {

                $calculator             = new Calculator($apply_amount, $first_payment_date, $interest_rate, $term, $term_period, $late_payment_penalties);
                $table_data             = $calculator->get_mortgage();
                $data['payable_amount'] = $calculator->payable_amount;

            } else if ($interest_type == 'one_time') {

                $calculator             = new Calculator($apply_amount, $first_payment_date, $interest_rate, 1, $term_period, $late_payment_penalties);
                $table_data             = $calculator->get_one_time();
                $data['payable_amount'] = $calculator->payable_amount;

            }

            $data['table_data']             = $table_data;
            $data['first_payment_date']     = $request->first_payment_date;
            $data['apply_amount']           = $request->apply_amount;
            $data['interest_rate']          = $request->interest_rate;
            $data['interest_type']          = $request->interest_type;
            $data['term']                   = $request->term;
            $data['term_period']            = $request->term_period;
            $data['late_payment_penalties'] = $request->late_payment_penalties;

            return view('backend.customer_portal.loan.calculator', $data);
        }
    }

    public function apply_loan(Request $request) {
        if ($request->isMethod('get')) {
            return view('backend.customer_portal.loan.apply_loan');
        } else if ($request->isMethod('post')) {
            @ini_set('max_execution_time', 0);
            @set_time_limit(0);

            $validator = Validator::make($request->all(), [
                'loan_product_id'    => 'required',
                'currency_id'        => 'required',
                'first_payment_date' => 'required',
                'applied_amount'     => 'required|numeric',
                'attachment'         => 'nullable|mimes:jpeg,png,jpg,doc,pdf,docx,zip',
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
                } else {
                    return redirect()->route('loans.apply_new')
                        ->withErrors($validator)
                        ->withInput();
                }
            }

            $attachment = "";
            if ($request->hasfile('attachment')) {
                $file       = $request->file('attachment');
                $attachment = time() . $file->getClientOriginalName();
                $file->move(public_path() . "/uploads/media/", $attachment);
            }

            DB::beginTransaction();

            $loan                         = new Loan();
            $loan->loan_product_id        = $request->input('loan_product_id');
            $loan->borrower_id            = auth()->id();
            $loan->currency_id            = $request->input('currency_id');
            $loan->first_payment_date     = $request->input('first_payment_date');
            $loan->applied_amount         = $request->input('applied_amount');
            $loan->late_payment_penalties = 0;
            $loan->attachment             = $attachment;
            $loan->description            = $request->input('description');
            $loan->remarks                = $request->input('remarks');
            $loan->created_user_id        = auth()->id();

            $loan->save();

            // Create Loan Repayments
            $calculator = new Calculator(
                $loan->applied_amount,
                $request->first_payment_date,
                $loan->loan_product->interest_rate,
                $loan->loan_product->term,
                $loan->loan_product->term_period,
                $loan->late_payment_penalties
            );

            if ($loan->loan_product->interest_type == 'flat_rate') {
                $repayments = $calculator->get_flat_rate();
            } else if ($loan->loan_product->interest_type == 'fixed_rate') {
                $repayments = $calculator->get_fixed_rate();
            } else if ($loan->loan_product->interest_type == 'mortgage') {
                $repayments = $calculator->get_mortgage();
            } else if ($loan->loan_product->interest_type == 'one_time') {
                $repayments = $calculator->get_one_time();
            }

            $loan->total_payable = $calculator->payable_amount;
            $loan->save();

            DB::commit();

            if ($loan->id > 0) {
                return redirect()->route('loans.my_loans')->with('success', _lang('Your Loan application submitted sucessfully and your application is now under review'));
            }
        }

    }

    public function loan_payment(Request $request, $loan_id) {
        if (request()->isMethod('get')) {
            $loan = Loan::where('id', $loan_id)->where('borrower_id', auth()->id())->first();
            return view('backend.customer_portal.loan.payment', compact('loan'));
        } else if (request()->isMethod('post')) {

            DB::beginTransaction();

            $loan      = Loan::where('id', $loan_id)->where('borrower_id', auth()->id())->first();
            $repayment = $loan->next_payment;

            //Create Transaction
            $penalty = date('Y-m-d') > $repayment->repayment_date ? $repayment->penalty : 0;
            $amount  = $repayment->amount_to_pay + $penalty;
            //$amount      = convert_currency(account_currency($loan->account_id), account_currency($request->account_id), $base_amount);

            //Check Available Balance
            if (get_account_balance($loan->currency_id) < $amount) {
                return back()->with('error', _lang('Insufficient balance !'));
            }

            //Create Debit Transactions
            $debit                  = new Transaction();
            $debit->user_id         = auth()->id();
            $debit->currency_id     = $loan->currency_id;
            $debit->amount          = $amount;
            $debit->dr_cr           = 'dr';
            $debit->type            = 'Loan_Repayment';
            $debit->method          = 'Online';
            $debit->status          = 2;
            $debit->note            = _lang('Loan Repayment');
            $debit->created_user_id = auth()->id();
            $debit->branch_id       = auth()->user()->branch_id;
            $debit->loan_id         = $loan->id;

            $debit->save();

            $loanpayment                 = new LoanPayment();
            $loanpayment->loan_id        = $loan->id;
            $loanpayment->paid_at        = date('Y-m-d');
            $loanpayment->late_penalties = $penalty;
            $loanpayment->interest       = $repayment->interest;
            $loanpayment->amount_to_pay  = $repayment->amount_to_pay;
            $loanpayment->remarks        = $request->remarks;
            $loanpayment->transaction_id = $debit->id;
            $loanpayment->repayment_id   = $repayment->id;
            $loanpayment->user_id        = auth()->id();

            $loanpayment->save();

            //Update Loan Balance
            $repayment->status = 1;
            $repayment->save();

            $loan->total_paid = $loan->total_paid + $repayment->amount_to_pay;
            if ($loan->total_paid >= $loan->applied_amount) {
                $loan->status = 2;
            }
            $loan->save();

            DB::commit();

            if (!$request->ajax()) {
                return redirect()->route('loans.my_loans')->with('success', _lang('Payment Made Sucessfully'));
            } else {
                return response()->json(['result' => 'success', 'action' => 'store', 'message' => _lang('Payment Made Sucessfully'), 'data' => $loanpayment, 'table' => '#loan_payments_table']);
            }
        }
    }

}