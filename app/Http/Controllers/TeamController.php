<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Validator;

class TeamController extends Controller {

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
        $teams = Team::all()->sortByDesc("id");
        return view('backend.website_management.team.list', compact('teams'));
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
            return view('backend.website_management.team.modal.create');
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
            'name'  => 'required',
            'image' => 'required|image',
            'role'  => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('teams.create')
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        $image = '';
        if ($request->hasfile('image')) {
            $file  = $request->file('image');
            $image = time() . $file->getClientOriginalName();
            $file->move(public_path() . "/uploads/media/", $image);
        }

        $team        = new Team();
        $team->name  = $request->input('name');
        $team->image = $image;
        $team->role  = $request->input('role');

        $team->save();

        $team->image = '<img src="' . media_images($team->image) . '" class="thumb-sm img-thumbnail"/>';

        if (!$request->ajax()) {
            return redirect()->route('teams.create')->with('success', _lang('Saved Successfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'store', 'message' => _lang('Saved Successfully'), 'data' => $team, 'table' => '#teams_table']);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id) {
        $team = Team::find($id);
        if (!$request->ajax()) {
            return back();
        } else {
            return view('backend.website_management.team.modal.edit', compact('team', 'id'));
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
            'name' => 'required',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('teams.edit', $id)
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        if ($request->hasfile('image')) {
            $file  = $request->file('image');
            $image = time() . $file->getClientOriginalName();
            $file->move(public_path() . "/uploads/media/", $image);
        }

        $team       = Team::find($id);
        $team->name = $request->input('name');
        if ($request->hasfile('image')) {
            $team->image = $image;
        }
        $team->role = $request->input('role');

        $team->save();
        $team->image = '<img src="' . media_images($team->image) . '" class="thumb-sm img-thumbnail"/>';

        if (!$request->ajax()) {
            return redirect()->route('teams.index')->with('success', _lang('Updated Successfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'update', 'message' => _lang('Updated Successfully'), 'data' => $team, 'table' => '#teams_table']);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $team = Team::find($id);
        $team->delete();
        return redirect()->route('teams.index')->with('success', _lang('Deleted Successfully'));
    }
}
