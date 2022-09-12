<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\DepositMethod;
use App\Models\DepositRequest;
use App\Models\GiftCard;
use App\Models\PaymentGateway;
use App\Models\Transaction;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepositController extends Controller {

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
        $deposit_methods = DepositMethod::where('status', 1)->get();
        return view('backend.customer_portal.deposit.manual_methods', compact('deposit_methods'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function automatic_methods() {
        $deposit_methods = PaymentGateway::where('status', 1)->get();
        return view('backend.customer_portal.deposit.automatic_methods', compact('deposit_methods'));
    }

    public function manual_deposit(Request $request, $methodId) {
        if ($request->isMethod('get')) {
            $deposit_method = DepositMethod::find($methodId);
            return view('backend.customer_portal.deposit.modal.manual_deposit', compact('deposit_method'));
        } else if ($request->isMethod('post')) {
            $deposit_method = DepositMethod::find($methodId);

            $min_amount = $deposit_method->minimum_amount;
            $max_amount = $deposit_method->maximum_amount;

            $validator = Validator::make($request->all(), [
                'requirements.*' => 'required',
                'amount'         => "required|numeric|min:$min_amount|max:$max_amount",
                'attachment'     => 'nullable|mimes:jpeg,JPEG,png,PNG,jpg,doc,pdf,docx,zip',
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
                } else {
                    return redirect()->route('deposit.manual_deposit')
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

            $depositRequest               = new DepositRequest();
            $depositRequest->user_id      = auth()->id();
            $depositRequest->method_id    = $methodId;
            $depositRequest->amount       = $request->amount;
            $depositRequest->description  = $request->description;
            $depositRequest->requirements = json_encode($request->requirements);
            $depositRequest->attachment   = $attachment;
            $depositRequest->save();

            if (!$request->ajax()) {
                return redirect()->route('deposit.manual_deposit')->with('success', _lang('Deposit Request Submited'));
            } else {
                return response()->json(['result' => 'success', 'action' => 'store', 'message' => _lang('Deposit Request Submited'), 'data' => $depositRequest, 'table' => '#unknown_table']);
            }

        }
    }

    public function automatic_deposit(Request $request, $methodId) {
        if ($request->isMethod('get')) {
            if ($request->ajax()) {
                $deposit_method = PaymentGateway::where('id', $methodId)->where('status', 1)->first();
                return view('backend.customer_portal.deposit.modal.automatic_deposit', compact('deposit_method'));
            }
            return redirect()->route('deposit.automatic_methods');
        } else if ($request->isMethod('post')) {
            $deposit_method = PaymentGateway::where('id', $methodId)->where('status', 1)->first();

            $min_amount = $deposit_method->minimum_amount;
            $max_amount = $deposit_method->maximum_amount;

            $validator = Validator::make($request->all(), [
                'amount' => "required|numeric|min:$min_amount|max:$max_amount",
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
                } else {
                    return redirect()->route('deposit.automatic_methods')
                        ->withErrors($validator)
                        ->withInput();
                }
            }

            //Charge
            $charge = $deposit_method->fixed_charge;
            $charge += ($deposit_method->charge_in_percentage / 100) * $request->amount;

            //Create Pending Transaction
            $deposit                  = new Transaction();
            $deposit->user_id         = auth()->id();
            $deposit->currency_id     = base_currency_id();
            $deposit->amount          = $request->amount;
            $deposit->fee             = $charge;
            $deposit->dr_cr           = 'cr';
            $deposit->type            = 'Deposit';
            $deposit->method          = $deposit_method->slug;
            $deposit->status          = 1;
            $deposit->note            = _lang('Deposit Via') . ' ' . $deposit_method->name;
            $deposit->gateway_id      = $deposit_method->id;
            $deposit->created_user_id = auth()->id();
            $deposit->branch_id       = auth()->user()->branch_id;

            $deposit->save();

            //Process Via Payment Gateway
            $gateway = '\App\Http\Controllers\Gateway\\' . $deposit_method->slug . '\\ProcessController';

            $data = $gateway::process($deposit);
            $data = json_decode($data);

            if (isset($data->error)) {
                $deposit->delete();
                return redirect()->route('deposit.automatic_methods')
                    ->with('error', $data->error_message);
            }

            $alert_col = 'col-lg-6 offset-lg-3';
            return view($data->view, compact('data', 'deposit', 'alert_col'));
        }
    }

    public function redeem_gift_card(Request $request) {
        if ($request->isMethod('get')) {
            $alert_col = 'col-lg-6 offset-lg-3';
            return view('backend.customer_portal.deposit.redeem_gift_card', compact('alert_col'));
        } else if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'code' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->route('deposit.redeem_gift_card')
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            //Check Code is valid
            $gift_card = GiftCard::where('code', $request->code)->where('status', 0)->first();
            if (!$gift_card) {
                return back()
                    ->with('error', _lang('Invalid or used gift card !'))
                    ->withInput();
            }

            //Create Credit Transactions
            $credit                  = new Transaction();
            $credit->user_id         = auth()->id();
            $credit->currency_id     = $gift_card->currency_id;
            $credit->amount          = $gift_card->amount;
            $credit->dr_cr           = 'cr';
            $credit->type            = 'Deposit';
            $credit->method          = 'GiftCard';
            $credit->status          = 2;
            $credit->note            = _lang('Redeem Gift Card');
            $credit->created_user_id = auth()->id();
            $credit->branch_id       = auth()->user()->branch_id;

            $credit->save();

            $gift_card->status  = 1;
            $gift_card->user_id = auth()->id();
            $gift_card->used_at = now();
            $gift_card->save();

            DB::commit();

            return redirect()->route('deposit.redeem_gift_card')->with('success', _lang('Gift Card Redeem Successfully'));

        }
    }

}