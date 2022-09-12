<?php

namespace App\Http\Controllers;

use App\Models\DepositRequest;
use App\Models\Transaction;
use App\Notifications\ApprovedDepositRequest;
use App\Notifications\RejectDepositRequest;
use DataTables;
use DB;
use Illuminate\Http\Request;

class DepositRequestController extends Controller {

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
        return view('backend.deposit_request.list');
    }

    public function get_table_data(Request $request) {

        $deposit_requests = DepositRequest::select('deposit_requests.*')
            ->with('user')
            ->with('method')
            ->with('method.currency')
            ->orderBy("deposit_requests.id", "desc");

        return Datatables::eloquent($deposit_requests)
            ->filter(function ($query) use ($request) {
                $status = $request->has('status') ? $request->status : 1;
                $query->where('status', $status);
            }, true)
            ->editColumn('user.name', function ($deposit_request) {
                return '<b>' . $deposit_request->user->name . ' </b><br>' . $deposit_request->user->email;
            })
            ->editColumn('amount', function ($deposit_request) {
                return decimalPlace($deposit_request->amount, currency($deposit_request->method->currency->name));
            })
            ->editColumn('status', function ($deposit_request) {
                return transaction_status($deposit_request->status);
            })
            ->addColumn('action', function ($deposit_request) {
                $actions = '<form action="' . action('DepositRequestController@destroy', $deposit_request['id']) . '" class="text-center" method="post">';
                $actions .= '<a href="' . action('DepositRequestController@show', $deposit_request['id']) . '" data-title="' . _lang('Transfer Details') . '" class="btn btn-outline-primary btn-sm ajax-modal"><i class="icofont-eye-alt"></i> ' . _lang('Details') . '</a>&nbsp;';
                $actions .= $deposit_request->status != 2 ? '<a href="' . action('DepositRequestController@approve', $deposit_request['id']) . '" class="btn btn-outline-success btn-sm"><i class="icofont-check-circled"></i> ' . _lang('Approve') . '</a>&nbsp;' : '';
                $actions .= $deposit_request->status != 0 ? '<a href="' . action('DepositRequestController@reject', $deposit_request['id']) . '" class="btn btn-outline-warning btn-sm"><i class="icofont-close-circled"></i> ' . _lang('Reject') . '</a>&nbsp;' : '';
                $actions .= csrf_field();
                $actions .= '<input name="_method" type="hidden" value="DELETE">';
                $actions .= '<button class="btn btn-outline-danger btn-sm btn-remove" type="submit"><i class="icofont-trash"></i> ' . _lang('Delete') . '</button>';
                $actions .= '</form>';

                return $actions;

            })
            ->setRowId(function ($deposit_request) {
                return "row_" . $deposit_request->id;
            })
            ->rawColumns(['user.name', 'status', 'action'])
            ->make(true);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        $depositrequest = DepositRequest::find($id);
        if (!$request->ajax()) {
            return back();
        } else {
            return view('backend.deposit_request.modal.view', compact('depositrequest', 'id'));
        }

    }

    /**
     * Approve Wire Transfer
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approve($id) {
        DB::beginTransaction();

        $depositRequest = DepositRequest::find($id);

        //Charge
        $charge = $depositRequest->method->fixed_charge;
        $charge += ($depositRequest->method->charge_in_percentage / 100) * $depositRequest->amount;

        //Create Transaction
        $transaction                  = new Transaction();
        $transaction->user_id         = $depositRequest->user_id;
        $transaction->currency_id     = $depositRequest->method->currency_id;
        $transaction->amount          = $depositRequest->amount - $charge;
        $transaction->fee             = $charge;
        $transaction->dr_cr           = 'cr';
        $transaction->type            = 'Deposit';
        $transaction->method          = $depositRequest->method->name;
        $transaction->status          = 2;
        $transaction->note            = _lang('Deposit Via') . ' ' . $depositRequest->method->name;
        $transaction->created_user_id = auth()->id();
        $transaction->branch_id       = auth()->user()->branch_id;

        $transaction->save();

        $depositRequest->status         = 2;
        $depositRequest->transaction_id = $transaction->id;
        $depositRequest->save();

        try {
            $transaction->user->notify(new ApprovedDepositRequest($transaction));
        } catch (\Exception $e) {}

        DB::commit();
        return redirect()->route('deposit_requests.index')->with('success', _lang('Request Approved'));
    }

    /**
     * Reject Wire Transfer
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reject($id) {
        DB::beginTransaction();
        $depositRequest = DepositRequest::find($id);

        if ($depositRequest->transaction_id != null) {
            $transaction = Transaction::find($depositRequest->transaction_id);
            $transaction->delete();
        }

        $depositRequest->status         = 0;
        $depositRequest->transaction_id = null;
        $depositRequest->save();

        try {
            $transaction->user->notify(new RejectDepositRequest($transaction));
        } catch (\Exception $e) {}

        DB::commit();
        return redirect()->route('deposit_requests.index')->with('success', _lang('Request Rejected'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $depositrequest = DepositRequest::find($id);
        if ($depositRequest->transaction_id != null) {
            $transaction = Transaction::find($depositRequest->transaction_id);
            $transaction->delete();
        }
        $depositrequest->delete();
        return redirect()->route('deposit_requests.index')->with('success', _lang('Deleted Successfully'));
    }
}