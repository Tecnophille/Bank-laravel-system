<?php

namespace App\Http\Controllers;

use App\Models\GiftCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GiftCardController extends Controller {

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
    public function index($status = 'active_gift_card') {
        $status = $status == 'active_gift_card' ? 0 : 1;

        if ($status == 0) {
            $title = _lang('Active Gift Card');
        } else if ($status == 1) {
            $title = _lang('Used Gift Card');
        }

        $giftcards = GiftCard::where('status', $status)
            ->orderBy('id', 'desc')
            ->get();

        return view('backend.gift_cards.list', compact('giftcards', 'title'));
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
            return view('backend.gift_cards.modal.create');
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
            'code'        => 'required',
            'currency_id' => 'required',
            'amount'      => 'required|numeric',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('gift_cards.create')
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        $giftcard              = new GiftCard();
        $giftcard->code        = $request->input('code');
        $giftcard->currency_id = $request->input('currency_id');
        $giftcard->amount      = $request->input('amount');
        $giftcard->branch_id   = auth()->user()->branch_id;

        $giftcard->save();

        $giftcard->currency_id = $giftcard->currency->name;
        $giftcard->status      = $giftcard->status == 0 ? xss_clean(show_status(_lang('Unused'), 'primary')) : xss_clean(show_status(_lang('Used'), 'danger'));
        $giftcard->amount      = decimalPlace($giftcard->amount, currency($giftcard->currency->name));

        if (!$request->ajax()) {
            return redirect()->route('gift_cards.create')->with('success', _lang('Saved Successfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'store', 'message' => _lang('Saved Successfully'), 'data' => $giftcard, 'table' => '#gift_cards_table']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        $giftcard = GiftCard::find($id);
        if (!$request->ajax()) {
            return back();
        } else {
            return view('backend.gift_cards.modal.view', compact('giftcard', 'id'));
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id) {
        $giftcard = GiftCard::where('id', $id)->where('status', 0)->first();
        if (!$giftcard) {
            return response()->json(['result' => 'error', 'message' => _lang('Sorry gift card is already used !')]);
        }
        if (!$request->ajax()) {
            return back();
        } else {
            return view('backend.gift_cards.modal.edit', compact('giftcard', 'id'));
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
            'code'        => 'required',
            'currency_id' => 'required',
            'amount'      => 'required|numeric',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('gift_cards.edit', $id)
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        $giftcard = GiftCard::find($id);
        if (!$giftcard) {
            return response()->json(['result' => 'error', 'message' => _lang('Sorry gift card is already used !')]);
        }
        $giftcard->currency_id = $request->input('currency_id');
        $giftcard->amount      = $request->input('amount');

        $giftcard->save();

        $giftcard->currency_id = $giftcard->currency->name;
        $giftcard->status      = $giftcard->status == 0 ? xss_clean(show_status(_lang('Unused'), 'primary')) : xss_clean(show_status(_lang('Used'), 'danger'));
        $giftcard->amount      = decimalPlace($giftcard->amount, currency($giftcard->currency->name));

        if (!$request->ajax()) {
            return redirect()->route('gift_cards.index')->with('success', _lang('Updated Successfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'update', 'message' => _lang('Updated Successfully'), 'data' => $giftcard, 'table' => '#gift_cards_table']);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $giftcard = GiftCard::find($id);
        $giftcard->delete();
        return redirect()->route('gift_cards.index')->with('success', _lang('Deleted Successfully'));
    }
}