<?php

namespace App\Http\Controllers;

use App\Models\Page;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller {

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
        $pages = Page::all()->sortByDesc("id");
        return view('backend.website_management.page.list', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        if (!$request->ajax()) {
            return view('backend.website_management.page.create');
        } else {
            return view('backend.website_management.page.modal.create');
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
            'trans.title' => 'required',
            'trans.body'  => 'required',
            'status'      => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('pages.create')
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        $page         = new Page();
        $page->slug   = $request->trans['title'];
        $page->status = $request->input('status');

        $page->save();

        if (!$request->ajax()) {
            return redirect()->route('pages.index')->with('success', _lang('Saved Sucessfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'store', 'message' => _lang('Saved Sucessfully'), 'data' => $page, 'table' => '#pages_table']);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id) {
        $page = Page::find($id);
        if (!$request->ajax()) {
            return view('backend.website_management.page.edit', compact('page', 'id'));
        } else {
            return view('backend.website_management.page.modal.edit', compact('page', 'id'));
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
            'trans.title' => 'required',
            'trans.body'  => 'required',
            'status'      => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('pages.edit', $id)
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        DB::beginTransaction();

        $page         = Page::find($id);
        $page->status = $request->input('status');

        $page->save();

        DB::commit();

        if (!$request->ajax()) {
            return redirect()->route('pages.index')->with('success', _lang('Updated Sucessfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'update', 'message' => _lang('Updated Sucessfully'), 'data' => $page, 'table' => '#pages_table']);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $page = Page::find($id);
        $page->delete();

        return redirect()->route('pages.index')->with('success', _lang('Deleted Sucessfully'));
    }
}