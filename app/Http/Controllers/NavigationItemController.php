<?php

namespace App\Http\Controllers;

use App\Models\NavigationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NavigationItemController extends Controller {

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
        $navigationitems = NavigationItem::all()->sortByDesc("id");
        return view('backend.site_navigation.navigation_item.list', compact('navigationitems'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $navigation_id) {
        if (!$request->ajax()) {
            return view('backend.site_navigation.navigation_item.create', compact('navigation_id'));
        } else {
            return view('backend.site_navigation.navigation_item.modal.create', compact('navigation_id'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $navigation_id) {
        $validator = Validator::make($request->all(), [
            'trans.name' => 'required',
            'type'       => 'required',
            'page_id'    => 'required_if:type,page',
            'url'        => 'required_if:type,dynamic_url,custom_url',
            'target'     => 'required',
            'status'     => 'required',
        ], [
            'page_id.required_if' => 'Page field is required',
            'url.required'        => 'URL field is required',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return back()->withErrors($validator)->withInput();
            }
        }

        $navigationitem                = new NavigationItem();
        $navigationitem->navigation_id = $navigation_id;
        $navigationitem->type          = $request->input('type');
        $navigationitem->page_id       = $request->input('page_id');
        $navigationitem->url           = $request->input('url');
        $navigationitem->icon          = $request->input('icon');
        $navigationitem->target        = $request->input('target');
        $navigationitem->css_class     = $request->input('css_class');
        $navigationitem->css_id        = $request->input('css_id');
        $navigationitem->position      = 9999;
        $navigationitem->status        = $request->input('status');

        $navigationitem->save();

        if (!$request->ajax()) {
            return redirect()->route('navigations.show', $navigation_id)->with('success', _lang('Saved Sucessfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'store', 'message' => _lang('Saved Sucessfully'), 'data' => $navigationitem, 'table' => '#navigation_items_table']);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id) {
        $navigationitem = NavigationItem::find($id);
        if (!$request->ajax()) {
            return view('backend.site_navigation.navigation_item.edit', compact('navigationitem', 'id'));
        } else {
            return view('backend.site_navigation.navigation_item.modal.edit', compact('navigationitem', 'id'));
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
            'trans.name' => 'required',
            'type'       => 'required',
            'page_id'    => 'required_if:type,page',
            'url'        => 'required_if:type,dynamic_url,custom_url',
            'target'     => 'required',
            'status'     => 'required',
        ], [
            'page_id.required_if' => 'Page field is required',
            'url.required'        => 'URL field is required',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return back()->withErrors($validator)->withInput();
            }
        }

        $navigationitem            = NavigationItem::find($id);
        $navigationitem->type      = $request->input('type');
        $navigationitem->page_id   = $request->input('page_id');
        $navigationitem->url       = $request->input('url');
        $navigationitem->icon      = $request->input('icon');
        $navigationitem->target    = $request->input('target');
        $navigationitem->css_class = $request->input('css_class');
        $navigationitem->css_id    = $request->input('css_id');
        $navigationitem->status    = $request->input('status');

        $navigationitem->save();

        if (!$request->ajax()) {
            return redirect()->route('navigations.show', $navigationitem->navigation_id)->with('success', _lang('Updated Sucessfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'update', 'message' => _lang('Updated Sucessfully'), 'data' => $navigationitem, 'table' => '#navigation_items_table']);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $navigationitem = NavigationItem::find($id);
        $navigationitem->delete();
        return back()->with('success', _lang('Deleted Sucessfully'));
    }
}