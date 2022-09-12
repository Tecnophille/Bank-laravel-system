<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Notifications\DepositMoney;
use DataTables;
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
    public function index() {
        return view('backend.deposit.list');
    }

    public function get_table_data() {

        $transactions = Transaction::select('transactions.*')
            ->with('user')
            ->with('currency')
            ->where('type', 'Deposit')
            ->orderBy("transactions.id", "desc");

        return Datatables::eloquent($transactions)
            ->editColumn('user.name', function ($transaction) {
                return '<b>' . $transaction->user->name . ' </b><br>' . $transaction->user->email;
            })
            ->editColumn('amount', function ($transaction) {
                return decimalPlace($transaction->amount, currency($transaction->currency->name));
            })
            ->editColumn('status', function ($transaction) {
                return transaction_status($transaction->status);
            })
            ->addColumn('action', function ($transaction) {
                return '<form action="' . action('DepositController@destroy', $transaction['id']) . '" class="text-center" method="post">'
                . '<a href="' . action('DepositController@show', $transaction['id']) . '" data-title="' . _lang('Transaction Details') . '" class="btn btn-primary btn-sm ajax-modal">' . _lang('View') . '</a>&nbsp;'
                . csrf_field()
                . '<input name="_method" type="hidden" value="DELETE">'
                . '<button class="btn btn-danger btn-sm btn-remove" type="submit">' . _lang('Delete') . '</button>'
                    . '</form>';
            })
            ->setRowId(function ($transaction) {
                return "row_" . $transaction->id;
            })
            ->rawColumns(['user.name', 'status', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        if (!$request->ajax()) {
            return view('backend.deposit.create');
        } else {
            return view('backend.deposit.modal.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        @ini_set('max_execution_time', 0);
        @set_time_limit(0);

        $validator = Validator::make($request->all(), [
            'user_email'  => 'required',
            'currency_id' => 'required',
            'amount'      => 'required|numeric|min:1.00',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return back()->withErrors($validator)->withInput();
            }
        }

        $user = User::where('email', $request->user_email)->where('user_type', 'customer')->first();

        if (!$user) {
            return back()->with('error', _lang('User Account not found !'))->withInput();
        }

        $transaction                  = new Transaction();
        $transaction->user_id         = $user->id;
        $transaction->currency_id     = $request->input('currency_id');
        $transaction->amount          = $request->input('amount');
        $transaction->dr_cr           = 'cr';
        $transaction->type            = 'Deposit';
        $transaction->method          = 'Manual';
        $transaction->status          = 2;
        $transaction->note            = $request->input('note');
        $transaction->created_user_id = auth()->id();
        $transaction->branch_id       = auth()->user()->branch_id;

        $transaction->save();

        try {
            $transaction->user->notify(new DepositMoney($transaction));
        } catch (\Exception $e) {}

        if (!$request->ajax()) {
            return back()->with('success', _lang('Deposit made successfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'store', 'message' => _lang('Deposit made successfully'), 'data' => $transaction, 'table' => '#transactions_table']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        $transaction = Transaction::find($id);
        if (!$request->ajax()) {
            return view('backend.deposit.view', compact('transaction', 'id'));
        } else {
            return view('backend.deposit.modal.view', compact('transaction', 'id'));
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $transaction = Transaction::find($id);
        $transaction->delete();
        return redirect()->route('deposits.index')->with('success', _lang('Deleted Successfully'));
    }
}