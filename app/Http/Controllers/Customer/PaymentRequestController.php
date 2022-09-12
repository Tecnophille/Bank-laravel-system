<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\PaymentRequest;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\PaymentCompleted;
use App\Notifications\PaymentRequest as PaymentRequestNotification;
use DataTables;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentRequestController extends Controller {

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
        return view('backend.customer_portal.payment_request.list');
    }

    public function get_table_data() {

        $paymentrequests = PaymentRequest::select('payment_requests.*')
            ->with(['currency', 'sender', 'receiver'])
            ->where('sender_id', auth()->id())
            ->orWhere('receiver_id', auth()->id())
            ->orderBy("payment_requests.id", "desc");

        return Datatables::eloquent($paymentrequests)
            ->editColumn('status', function ($paymentrequest) {
                return transaction_status($paymentrequest->status);
            })
            ->editColumn('amount', function ($paymentrequest) {
                return decimalPlace($paymentrequest->amount, currency($paymentrequest->currency->name));
            })
            ->addColumn('action', function ($paymentrequest) {
                if ($paymentrequest->status == 1 && $paymentrequest->sender_id == auth()->id()) {
                    return '<div class="text-center"><a href="' . route('payment_requests.show', $paymentrequest->id) . '" data-title="' . _lang('Payment Request Details') . '" class="btn btn-primary btn-sm ajax-modal">' . _lang('View') . '</a>&nbsp;'
                    . '<a href="' . route('payment_requests.cancel', $paymentrequest->id) . '" class="btn btn-danger btn-sm btn-remove-2">' . _lang('Cancel') . '</a></div>';
                } else if ($paymentrequest->status == 1 && $paymentrequest->receiver_id == auth()->id()) {
                    return '<div class="text-center"><a href="' . route('payment_requests.show', $paymentrequest->id) . '" data-title="' . _lang('Payment Request Details') . '" class="btn btn-primary btn-sm ajax-modal">' . _lang('View') . '</a>&nbsp;'
                    . '<a href="' . route('payment_requests.pay_now', encrypt($paymentrequest->id)) . '" class="btn btn-success btn-sm">' . _lang('Pay Now') . '</a></div>';
                }

                return '<div class="text-center"><a href="' . route('payment_requests.show', $paymentrequest->id) . '" data-title="' . _lang('Payment Request Details') . '" class="btn btn-primary btn-sm ajax-modal">' . _lang('View') . '</a></div>';
            })
            ->setRowId(function ($paymentrequest) {
                return "row_" . $paymentrequest->id;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $alert_col = 'col-lg-8 offset-lg-2';
        return view('backend.customer_portal.payment_request.create', compact('alert_col'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'receiver_account' => 'required',
            'currency_id'      => 'required',
            'amount'           => 'required|numeric',
            'description'      => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('payment_requests.create')
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        $user = User::where('email', $request->receiver_account)
            ->where('user_type', 'customer')
            ->where('id', '!=', auth()->id())
            ->first();

        if (!$user) {
            return back()->with('error', _lang('Receiver Account not found !'))->withInput();
        }

        $paymentrequest              = new PaymentRequest();
        $paymentrequest->currency_id = $request->input('currency_id');
        $paymentrequest->amount      = $request->input('amount');
        $paymentrequest->status      = 1;
        $paymentrequest->description = $request->input('description');
        $paymentrequest->sender_id   = auth()->id();
        $paymentrequest->receiver_id = $user->id;
        $paymentrequest->branch_id   = auth()->user()->branch_id;

        $paymentrequest->save();

        try {
            $paymentrequest->receiver->notify(new PaymentRequestNotification($paymentrequest));
        } catch (\Exception $e) {}

        return redirect()->route('payment_requests.create')->with('success', _lang('Payment Request Sent'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        $paymentrequest = PaymentRequest::find($id);
        if (!$request->ajax()) {
            return view('backend.customer_portal.payment_request.view', compact('paymentrequest', 'id'));
        } else {
            return view('backend.customer_portal.payment_request.modal.view', compact('paymentrequest', 'id'));
        }

    }

    /**
     * Payment Screen
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function pay_now(Request $request, $id) {
        $id = decrypt($id);

        if ($request->isMethod('get')) {
            $alert_col      = 'col-lg-8 offset-lg-2';
            $paymentrequest = PaymentRequest::where('id', $id)->where('receiver_id', auth()->id())->first();

            $charge = 0;

            if (get_option('transfer_fee_type') == 'percentage') {
                $charge = (get_option('transfer_fee', 0) / 100) * $paymentrequest->amount;
            } else if (get_option('transfer_fee_type') == 'fixed') {
                $charge = convert_currency(base_currency_id(), $paymentrequest->currency_id, get_option('transfer_fee', 0));
            }

            return view('backend.customer_portal.payment_request.pay_now', compact('paymentrequest', 'charge', 'alert_col'));
        } else if ($request->isMethod('post')) {
            $paymentrequest = PaymentRequest::where('id', $id)
                ->where('receiver_id', auth()->id())
                ->where('status', 1)
                ->first();

            $charge = 0;

            if (get_option('transfer_fee_type') == 'percentage') {
                $charge = (get_option('transfer_fee', 0) / 100) * $paymentrequest->amount;
            } else if (get_option('transfer_fee_type') == 'fixed') {
                $charge = convert_currency(base_currency_id(), $paymentrequest->currency_id, get_option('transfer_fee', 0));
            }

            //Check Available Balance
            if (get_account_balance($paymentrequest->currency_id) < $paymentrequest->amount + $charge) {
                return back()->with('error', _lang('Insufficient balance !'))->withInput();
            }

            DB::beginTransaction();

            //Create Debit Transactions
            $debit                  = new Transaction();
            $debit->user_id         = auth()->id();
            $debit->currency_id     = $paymentrequest->currency_id;
            $debit->amount          = $paymentrequest->amount + $charge;
            $debit->fee             = $charge;
            $debit->dr_cr           = 'dr';
            $debit->type            = 'Payment';
            $debit->method          = 'Online';
            $debit->status          = 2;
            $debit->note            = $request->input('note');
            $debit->created_user_id = auth()->id();
            $debit->branch_id       = auth()->user()->branch_id;

            $debit->save();

            //Create Credit Transactions
            $credit                  = new Transaction();
            $credit->user_id         = $paymentrequest->sender_id;
            $credit->currency_id     = $paymentrequest->currency_id;
            $credit->amount          = $paymentrequest->amount;
            $credit->dr_cr           = 'cr';
            $credit->type            = 'Payment';
            $credit->method          = 'Online';
            $credit->status          = 2;
            $credit->note            = $request->input('note');
            $credit->created_user_id = auth()->id();
            $credit->branch_id       = auth()->user()->branch_id;
            $credit->parent_id       = $debit->id;

            $credit->save();

            $paymentrequest->status = 2;
            $paymentrequest->save();

            DB::commit();

            try {
                $paymentrequest->sender->notify(new PaymentCompleted($paymentrequest));
            } catch (\Exception $e) {}

            return redirect()->route('payment_requests.index')->with('success', _lang('Payment Made Successfully'));

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel($id) {
        $paymentrequest = PaymentRequest::where('id', $id)
            ->where('status', 1)
            ->where('sender_id', auth()->id())
            ->first();

        $paymentrequest->status = 0;
        $paymentrequest->save();
        return redirect()->route('payment_requests.index')->with('success', _lang('Payment Request Cancelled'));
    }
}