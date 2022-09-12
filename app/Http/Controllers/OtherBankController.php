<?php

namespace App\Http\Controllers;

use App\Models\OtherBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OtherBankController extends Controller {

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
        $otherbanks = OtherBank::all()->sortByDesc("id");
        return view('backend.other_bank.list', compact('otherbanks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        if (!$request->ajax()) {
            return view('backend.other_bank.create');
        } else {
            return view('backend.other_bank.modal.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'                    => 'required',
            'swift_code'              => 'required',
            'minimum_transfer_amount' => 'required|numeric',
            'maximum_transfer_amount' => 'required|numeric',
            'fixed_charge'            => 'required|numeric',
            'charge_in_percentage'    => 'required|numeric',
            'descriptions'            => '',
            'status'                  => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('other_banks.create')
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        $otherbank                          = new OtherBank();
        $otherbank->name                    = $request->input('name');
        $otherbank->swift_code              = $request->input('swift_code');
        $otherbank->bank_country            = $request->input('bank_country');
        $otherbank->bank_currency           = $request->input('bank_currency');
        $otherbank->minimum_transfer_amount = $request->input('minimum_transfer_amount');
        $otherbank->maximum_transfer_amount = $request->input('maximum_transfer_amount');
        $otherbank->fixed_charge            = $request->input('fixed_charge');
        $otherbank->charge_in_percentage    = $request->input('charge_in_percentage');
        $otherbank->descriptions            = $request->input('descriptions');
        $otherbank->status                  = $request->input('status');

        $otherbank->save();

        $otherbank->status = status($otherbank->status);

        if (!$request->ajax()) {
            return redirect()->route('other_banks.create')->with('success', _lang('Saved Successfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'store', 'message' => _lang('Saved Successfully'), 'data' => $otherbank, 'table' => '#other_banks_table']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        $otherbank = OtherBank::find($id);
        if (!$request->ajax()) {
            return view('backend.other_bank.view', compact('otherbank', 'id'));
        } else {
            return view('backend.other_bank.modal.view', compact('otherbank', 'id'));
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id) {
        $otherbank = OtherBank::find($id);
        if (!$request->ajax()) {
            return view('backend.other_bank.edit', compact('otherbank', 'id'));
        } else {
            return view('backend.other_bank.modal.edit', compact('otherbank', 'id'));
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'name'                    => 'required',
            'swift_code'              => 'required',
            'minimum_transfer_amount' => 'required|numeric',
            'maximum_transfer_amount' => 'required|numeric',
            'fixed_charge'            => 'required|numeric',
            'charge_in_percentage'    => 'required|numeric',
            'descriptions'            => '',
            'status'                  => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('other_banks.edit', $id)
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        $otherbank                          = OtherBank::find($id);
        $otherbank->name                    = $request->input('name');
        $otherbank->swift_code              = $request->input('swift_code');
        $otherbank->bank_country            = $request->input('bank_country');
        $otherbank->bank_currency           = $request->input('bank_currency');
        $otherbank->minimum_transfer_amount = $request->input('minimum_transfer_amount');
        $otherbank->maximum_transfer_amount = $request->input('maximum_transfer_amount');
        $otherbank->fixed_charge            = $request->input('fixed_charge');
        $otherbank->charge_in_percentage    = $request->input('charge_in_percentage');
        $otherbank->descriptions            = $request->input('descriptions');
        $otherbank->status                  = $request->input('status');

        $otherbank->save();

        $otherbank->status = status($otherbank->status);

        if (!$request->ajax()) {
            return redirect()->route('other_banks.index')->with('success', _lang('Updated Successfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'update', 'message' => _lang('Updated Successfully'), 'data' => $otherbank, 'table' => '#other_banks_table']);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $otherbank = OtherBank::find($id);
        $otherbank->delete();
        return redirect()->route('other_banks.index')->with('success', _lang('Deleted Successfully'));
    }
}