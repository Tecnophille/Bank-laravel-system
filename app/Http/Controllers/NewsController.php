<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller {

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
        $newss = News::all()->sortByDesc("id");
        return view('backend.website_management.news.list', compact('newss'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        if (!$request->ajax()) {
            return view('backend.website_management.news.create');
        } else {
            return view('backend.website_management.news.modal.create');
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
            'trans.title'             => 'required',
            'trans.short_description' => 'required',
            'image'                   => 'nullable|image',
            'status'                  => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('news.create')
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        $image = "";
        if ($request->hasfile('image')) {
            $file  = $request->file('image');
            $image = time() . $file->getClientOriginalName();
            $file->move(public_path() . "/uploads/media/", $image);
        }

        $news                  = new News();
        $news->image           = $image;
        $page->slug            = $request->trans['title'];
        $news->status          = $request->input('status');
        $news->created_user_id = auth()->id();

        $news->save();

        if (!$request->ajax()) {
            return redirect()->route('news.create')->with('success', _lang('Saved Successfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'store', 'message' => _lang('Saved Successfully'), 'data' => $news, 'table' => '#news_table']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id) {
        $news = News::find($id);
        if (!$request->ajax()) {
            return view('backend.website_management.news.edit', compact('news', 'id'));
        } else {
            return view('backend.website_management.news.modal.edit', compact('news', 'id'));
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
            'trans.title'             => 'required',
            'trans.short_description' => 'required',
            'image'                   => 'nullable|image',
            'status'                  => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('news.edit', $id)
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        if ($request->hasfile('image')) {
            $file  = $request->file('image');
            $image = time() . $file->getClientOriginalName();
            $file->move(public_path() . "/uploads/media/", $image);
        }

        $news = News::find($id);
        if ($request->hasfile('image')) {
            $news->profile_piimagecture = $image;
        }
        $news->status = $request->input('status');

        $news->save();

        if (!$request->ajax()) {
            return redirect()->route('news.index')->with('success', _lang('Updated Successfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'update', 'message' => _lang('Updated Successfully'), 'data' => $news, 'table' => '#news_table']);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $news = News::find($id);
        $news->delete();
        return redirect()->route('news.index')->with('success', _lang('Deleted Successfully'));
    }
}