<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller {

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
        $roles = Role::all()->sortByDesc("id");
        return view('backend.system_user.role.list', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        if (!$request->ajax()) {
            return view('backend.system_user.role.create');
        } else {
            return view('backend.system_user.role.modal.create');
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
            'name'        => 'required|max:50',
            'description' => '',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('roles.create')
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        $role              = new Role();
        $role->name        = $request->input('name');
        $role->description = $request->input('description');

        $role->save();

        if (!$request->ajax()) {
            return redirect()->route('roles.create')->with('success', _lang('Saved successfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'store', 'message' => _lang('Saved successfully'), 'data' => $role, 'table' => '#roles_table']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        $role = Role::find($id);
        if (!$request->ajax()) {
            return view('backend.system_user.role.view', compact('role', 'id'));
        } else {
            return view('backend.system_user.role.modal.view', compact('role', 'id'));
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id) {
        $role = Role::find($id);
        if (!$request->ajax()) {
            return view('backend.system_user.role.edit', compact('role', 'id'));
        } else {
            return view('backend.system_user.role.modal.edit', compact('role', 'id'));
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
            'name'        => 'required|max:50',
            'description' => '',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('roles.edit', $id)
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        $role              = Role::find($id);
        $role->name        = $request->input('name');
        $role->description = $request->input('description');

        $role->save();

        if (!$request->ajax()) {
            return redirect()->route('roles.index')->with('success', _lang('Updated successfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'update', 'message' => _lang('Updated successfully'), 'data' => $role, 'table' => '#roles_table']);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $role = Role::find($id);
        $role->delete();
        return redirect()->route('roles.index')->with('success', _lang('Deleted successfully'));
    }
}