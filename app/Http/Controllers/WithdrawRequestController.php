<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\WithdrawRequest;
use App\Notifications\ApprovedWithdrawRequest;
use App\Notifications\RejectWithdrawRequest;
use DataTables;
use DB;
use Illuminate\Http\Request;

class WithdrawRequestController extends Controller {

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
        return view('backend.withdraw_request.list');
    }

    public function get_table_data(Request $request) {

        $withdraw_requests = WithdrawRequest::select('withdraw_requests.*')
            ->with('user')
            ->with('method')
            ->with('method.currency')
            ->orderBy("withdraw_requests.id", "desc");

        return Datatables::eloquent($withdraw_requests)
            ->filter(function ($query) use ($request) {
                $status = $request->has('status') ? $request->status : 1;
                $query->where('status', $status);
            }, true)
            ->editColumn('user.name', function ($withdraw_request) {
                return '<b>' . $withdraw_request->user->name . ' </b><br>' . $withdraw_request->user->email;
            })
            ->editColumn('amount', function ($withdraw_request) {
                return decimalPlace($withdraw_request->amount, currency($withdraw_request->method->currency->name));
            })
            ->editColumn('status', function ($withdraw_request) {
                return transaction_status($withdraw_request->status);
            })
            ->addColumn('action', function ($withdraw_request) {
                $actions = '<form action="' . action('WithdrawRequestController@destroy', $withdraw_request['id']) . '" class="text-center" method="post">';
                $actions .= '<a href="' . action('WithdrawRequestController@show', $withdraw_request['id']) . '" data-title="' . _lang('Transfer Details') . '" class="btn btn-outline-primary btn-sm ajax-modal"><i class="icofont-eye-alt"></i> ' . _lang('Details') . '</a>&nbsp;';
                $actions .= $withdraw_request->status != 2 ? '<a href="' . action('WithdrawRequestController@approve', $withdraw_request['id']) . '" class="btn btn-outline-success btn-sm"><i class="icofont-check-circled"></i> ' . _lang('Approve') . '</a>&nbsp;' : '';
                $actions .= $withdraw_request->status != 0 ? '<a href="' . action('WithdrawRequestController@reject', $withdraw_request['id']) . '" class="btn btn-outline-warning btn-sm"><i class="icofont-close-circled"></i> ' . _lang('Reject') . '</a>&nbsp;' : '';
                $actions .= csrf_field();
                $actions .= '<input name="_method" type="hidden" value="DELETE">';
                $actions .= '<button class="btn btn-outline-danger btn-sm btn-remove" type="submit"><i class="icofont-trash"></i> ' . _lang('Delete') . '</button>';
                $actions .= '</form>';

                return $actions;

            })
            ->setRowId(function ($withdraw_request) {
                return "row_" . $withdraw_request->id;
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
        $withdrawRequest = WithdrawRequest::find($id);
        if (!$request->ajax()) {
            return back();
        } else {
            return view('backend.withdraw_request.modal.view', compact('withdrawRequest', 'id'));
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

        $withdrawRequest         = WithdrawRequest::find($id);
        $withdrawRequest->status = 2;
        $withdrawRequest->save();

        $transaction         = Transaction::find($withdrawRequest->transaction_id);
        $transaction->status = 2;
        $transaction->save();

        try {
            $transaction->user->notify(new ApprovedWithdrawRequest($transaction));
        } catch (\Exception $e) {}

        DB::commit();
        return redirect()->route('withdraw_requests.index')->with('success', _lang('Request Approved'));
    }

    /**
     * Reject Wire Transfer
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reject($id) {
        DB::beginTransaction();
        $withdrawRequest = WithdrawRequest::find($id);

        $transaction         = Transaction::find($withdrawRequest->transaction_id);
        $transaction->status = 0;
        $transaction->save();

        $withdrawRequest->status = 0;
        $withdrawRequest->save();

        try {
            $transaction->user->notify(new RejectWithdrawRequest($transaction));
        } catch (\Exception $e) {}

        DB::commit();
        return redirect()->route('withdraw_requests.index')->with('success', _lang('Request Rejected'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $withdrawRequest = WithdrawRequest::find($id);
        if ($withdrawRequest->transaction_id != null) {
            $transaction = Transaction::find($withdrawRequest->transaction_id);
            $transaction->delete();
        }
        $withdrawRequest->delete();
        return redirect()->route('deposit_requests.index')->with('success', _lang('Deleted Successfully'));
    }
}