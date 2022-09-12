<?php

namespace App\Http\Controllers;

use App\Models\Navigation;
use App\Models\NavigationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NavigationController extends Controller {

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
        $navigations = Navigation::all()->sortByDesc("id");
        return view('backend.site_navigation.list', compact('navigations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        if (!$request->ajax()) {
            return view('backend.site_navigation.create');
        } else {
            return view('backend.site_navigation.modal.create');
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
            'name'   => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('navigations.create')
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        $navigation         = new Navigation();
        $navigation->name   = $request->input('name');
        $navigation->status = $request->input('status');

        $navigation->save();
        $navigation->status = $navigation->status == 1 ? _lang('Active') : _lang('In-Active');

        if (!$request->ajax()) {
            return redirect()->route('navigations.index')->with('success', _lang('Saved Sucessfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'store', 'message' => _lang('Saved Sucessfully'), 'data' => $navigation, 'table' => '#navigations_table']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        $navigation      = Navigation::find($id);
        $navigationitems = $navigation->navigationItems;
        return view('backend.site_navigation.view', compact('navigation', 'navigationitems', 'id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id) {
        $navigation = Navigation::find($id);
        if (!$request->ajax()) {
            return view('backend.site_navigation.edit', compact('navigation', 'id'));
        } else {
            return view('backend.site_navigation.modal.edit', compact('navigation', 'id'));
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
            'name'   => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('navigations.edit', $id)
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        $navigation         = Navigation::find($id);
        $navigation->name   = $request->input('name');
        $navigation->status = $request->input('status');

        $navigation->save();
        $navigation->status = $navigation->status == 1 ? _lang('Active') : _lang('In-Active');

        if (!$request->ajax()) {
            return redirect()->route('navigations.index')->with('success', _lang('Updated Sucessfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'update', 'message' => _lang('Updated Sucessfully'), 'data' => $navigation, 'table' => '#navigations_table']);
        }

    }

    /**
     * Store navigation Sorting
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store_sorting(Request $request) {
        $order = 1;

        $array = json_decode($request->sortable_menu);

        foreach ($array as $menu_item) {
            $navigationitem            = NavigationItem::find($menu_item->id);
            $navigationitem->position  = $order;
            $navigationitem->parent_id = null;
            $navigationitem->save();
            $order++;

            $this->check_child($menu_item, $order);
        }

        return back()->with('success', _lang('Saved Sucessfully'));
    }

    private function check_child($object, $order) {
        if (isset($object->children)) {
            foreach ($object->children as $child_menu) {
                $navigationitem            = NavigationItem::find($child_menu->id);
                $navigationitem->position  = $order;
                $navigationitem->parent_id = $object->id;
                $navigationitem->save();
                $order++;
                $this->check_child($child_menu, $order);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $navigation = Navigation::find($id);
        $navigation->delete();
        return redirect()->route('navigations.index')->with('success', _lang('Deleted Sucessfully'));
    }
}