<?php

namespace App\Http\Controllers;

use App\Models\FDRPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FDRPlanController extends Controller {

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
        $fdrplans = FDRPlan::all()->sortByDesc("id");
        return view('backend.fdr_plan.list', compact('fdrplans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        if (!$request->ajax()) {
            return back();
        } else {
            return view('backend.fdr_plan.modal.create');
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
            'name'           => 'required|max:50',
            'minimum_amount' => 'required|numeric',
            'maximum_amount' => 'required|numeric',
            'interest_rate'  => 'required|numeric',
            'duration'       => 'required|integer',
            'duration_type'  => 'required',
            'status'         => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('fdr_plans.create')
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        $fdrplan                 = new FDRPlan();
        $fdrplan->name           = $request->input('name');
        $fdrplan->minimum_amount = $request->input('minimum_amount');
        $fdrplan->maximum_amount = $request->input('maximum_amount');
        $fdrplan->interest_rate  = $request->input('interest_rate');
        $fdrplan->duration       = $request->input('duration');
        $fdrplan->duration_type  = $request->input('duration_type');
        $fdrplan->status         = $request->input('status');
        $fdrplan->description    = $request->input('description');

        $fdrplan->save();

        $fdrplan->minimum_amount = decimalPlace($fdrplan->minimum_amount, currency());
        $fdrplan->maximum_amount = decimalPlace($fdrplan->maximum_amount, currency());
        $fdrplan->interest_rate  = $fdrplan->interest_rate . ' %';
        $fdrplan->status         = status($fdrplan->status);
        $fdrplan->duration       = $fdrplan->duration . ' ' . ucwords($fdrplan->duration_type);

        if (!$request->ajax()) {
            return redirect()->route('fdr_plans.create')->with('success', _lang('Saved Successfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'store', 'message' => _lang('Saved Successfully'), 'data' => $fdrplan, 'table' => '#fdr_plans_table']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        $fdrplan = FDRPlan::find($id);
        if (!$request->ajax()) {
            return back();
        } else {
            return view('backend.fdr_plan.modal.view', compact('fdrplan', 'id'));
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id) {
        $fdrplan = FDRPlan::find($id);
        if (!$request->ajax()) {
            return back();
        } else {
            return view('backend.fdr_plan.modal.edit', compact('fdrplan', 'id'));
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
            'name'           => 'required|max:50',
            'minimum_amount' => 'required|numeric',
            'maximum_amount' => 'required|numeric',
            'interest_rate'  => 'required|numeric',
            'duration'       => 'required|integer',
            'duration_type'  => 'required',
            'status'         => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('fdr_plans.edit', $id)
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        $fdrplan                 = FDRPlan::find($id);
        $fdrplan->name           = $request->input('name');
        $fdrplan->minimum_amount = $request->input('minimum_amount');
        $fdrplan->maximum_amount = $request->input('maximum_amount');
        $fdrplan->interest_rate  = $request->input('interest_rate');
        $fdrplan->duration       = $request->input('duration');
        $fdrplan->duration_type  = $request->input('duration_type');
        $fdrplan->status         = $request->input('status');
        $fdrplan->description    = $request->input('description');

        $fdrplan->save();

        $fdrplan->minimum_amount = decimalPlace($fdrplan->minimum_amount, currency());
        $fdrplan->maximum_amount = decimalPlace($fdrplan->maximum_amount, currency());
        $fdrplan->interest_rate  = $fdrplan->interest_rate . ' %';
        $fdrplan->status         = status($fdrplan->status);
        $fdrplan->duration       = $fdrplan->duration . ' ' . ucwords($fdrplan->duration_type);

        if (!$request->ajax()) {
            return redirect()->route('fdr_plans.index')->with('success', _lang('Updated Successfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'update', 'message' => _lang('Updated Successfully'), 'data' => $fdrplan, 'table' => '#fdr_plans_table']);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $fdrplan = FDRPlan::find($id);
        $fdrplan->delete();
        return redirect()->route('fdr_plans.index')->with('success', _lang('Deleted Successfully'));
    }
}