<?php

namespace App\Http\Controllers;

use App\Models\WithdrawMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WithdrawMethodController extends Controller {

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
        $withdrawmethods = WithdrawMethod::all()->sortByDesc("id");
        return view('backend.withdraw_method.list', compact('withdrawmethods'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        if (!$request->ajax()) {
            return view('backend.withdraw_method.create');
        } else {
            return view('backend.withdraw_method.modal.create');
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
            'name'                 => 'required',
            'image'                => 'nullable|image',
            'currency_id'          => 'required',
            'minimum_amount'       => 'required|numeric',
            'maximum_amount'       => 'required|numeric',
            'fixed_charge'         => 'required|numeric',
            'charge_in_percentage' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('withdraw_methods.create')
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        $image = 'default.png';
        if ($request->hasfile('image')) {
            $file  = $request->file('image');
            $image = time() . $file->getClientOriginalName();
            $file->move(public_path() . "/uploads/media/", $image);
        }

        $withdrawmethod                       = new WithdrawMethod();
        $withdrawmethod->name                 = $request->input('name');
        $withdrawmethod->image                = $image;
        $withdrawmethod->currency_id          = $request->input('currency_id');
        $withdrawmethod->minimum_amount       = $request->input('minimum_amount');
        $withdrawmethod->maximum_amount       = $request->input('maximum_amount');
        $withdrawmethod->fixed_charge         = $request->input('fixed_charge');
        $withdrawmethod->charge_in_percentage = $request->input('charge_in_percentage');
        $withdrawmethod->descriptions         = $request->input('descriptions');
        $withdrawmethod->status               = $request->input('status');
        $withdrawmethod->requirements         = json_encode($request->input('requirements'));

        $withdrawmethod->save();

        if (!$request->ajax()) {
            return redirect()->route('withdraw_methods.create')->with('success', _lang('Saved Successfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'store', 'message' => _lang('Saved Successfully'), 'data' => $withdrawmethod, 'table' => '#deposit_methods_table']);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id) {
        $withdrawmethod = WithdrawMethod::find($id);
        if (!$request->ajax()) {
            return view('backend.withdraw_method.edit', compact('withdrawmethod', 'id'));
        } else {
            return view('backend.withdraw_method.modal.edit', compact('withdrawmethod', 'id'));
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
            'name'                 => 'required',
            'image'                => 'nullable|image',
            'currency_id'          => 'required',
            'minimum_amount'       => 'required|numeric',
            'maximum_amount'       => 'required|numeric',
            'fixed_charge'         => 'required|numeric',
            'charge_in_percentage' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('withdraw_methods.edit', $id)
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        if ($request->hasfile('image')) {
            $file  = $request->file('image');
            $image = time() . $file->getClientOriginalName();
            $file->move(public_path() . "/uploads/media/", $image);
        }

        $withdrawmethod       = WithdrawMethod::find($id);
        $withdrawmethod->name = $request->input('name');
        if ($request->hasfile('image')) {
            $withdrawmethod->image = $image;
        }
        $withdrawmethod->currency_id          = $request->input('currency_id');
        $withdrawmethod->minimum_amount       = $request->input('minimum_amount');
        $withdrawmethod->maximum_amount       = $request->input('maximum_amount');
        $withdrawmethod->fixed_charge         = $request->input('fixed_charge');
        $withdrawmethod->charge_in_percentage = $request->input('charge_in_percentage');
        $withdrawmethod->descriptions         = $request->input('descriptions');
        $withdrawmethod->status               = $request->input('status');
        $withdrawmethod->requirements         = json_encode($request->input('requirements'));

        $withdrawmethod->save();

        if (!$request->ajax()) {
            return redirect()->route('withdraw_methods.index')->with('success', _lang('Updated Successfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'update', 'message' => _lang('Updated Successfully'), 'data' => $withdrawmethod, 'table' => '#deposit_methods_table']);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $withdrawmethod = WithdrawMethod::find($id);
        $withdrawmethod->delete();
        return redirect()->route('withdraw_methods.index')->with('success', _lang('Deleted Successfully'));
    }
}