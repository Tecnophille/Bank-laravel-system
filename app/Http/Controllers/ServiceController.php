<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller {

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
        $services = Service::all()->sortByDesc("id");
        return view('backend.website_management.service.list', compact('services'));
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
            return view('backend.website_management.service.modal.create');
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
            'icon'        => 'required',
            'trans.title' => 'required',
            'trans.body'  => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('services.create')
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        $service       = new Service();
        $service->icon = $request->input('icon');

        $service->save();

        $service->title = $service->translation->title;
        $service->body  = $service->translation->body;

        if (!$request->ajax()) {
            return redirect()->route('services.create')->with('success', _lang('Saved Successfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'store', 'message' => _lang('Saved Successfully'), 'data' => $service, 'table' => '#services_table']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        $service = Service::find($id);
        if (!$request->ajax()) {
            return back();
        } else {
            return view('backend.website_management.service.modal.view', compact('service', 'id'));
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id) {
        $service = Service::find($id);
        if (!$request->ajax()) {
            return back();
        } else {
            return view('backend.website_management.service.modal.edit', compact('service', 'id'));
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
            'icon'        => 'required',
            'trans.title' => 'required',
            'trans.body'  => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('services.edit', $id)
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        $service       = Service::find($id);
        $service->icon = $request->input('icon');

        $service->save();

        $service->title = $service->translation->title;
        $service->body  = $service->translation->body;

        if (!$request->ajax()) {
            return redirect()->route('services.index')->with('success', _lang('Updated Successfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'update', 'message' => _lang('Updated Successfully'), 'data' => $service, 'table' => '#services_table']);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $service = Service::find($id);
        $service->delete();
        return redirect()->route('services.index')->with('success', _lang('Deleted Successfully'));
    }
}