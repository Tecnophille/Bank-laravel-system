<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupportTicketController extends Controller {

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
    public function my_tickets(Request $request) {
        if ($request->status == 'active') {
            $status = 1;
            $title  = _lang('Active Tickets');
        } else if ($request->status == 'pending') {
            $status = 0;
            $title  = _lang('Pending Tickets');
        } else {
            $status = 2;
            $title  = _lang('Closed Tickets');
        }

        $supporttickets = SupportTicket::select('support_tickets.*')
            ->where('status', $status)
            ->with(['user', 'created_by'])
            ->where('user_id', auth()->id())
            ->orderBy("support_tickets.id", "desc")
            ->get();

        return view('backend.customer_portal.support_ticket.list', compact('supporttickets', 'status', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_ticket(Request $request) {
        if ($request->isMethod('get')) {
            $alert_col = 'col-lg-8 offset-lg-2';
            return view('backend.customer_portal.support_ticket.create', compact('alert_col'));
        } else {
            $validator = Validator::make($request->all(), [
                'subject'    => 'required',
                'message'    => 'required',
                'attachment' => 'nullable|mimes:jpeg,JPEG,png,PNG,jpg,doc,pdf,docx,zip',
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
                } else {
                    return redirect()->route('tickets.create_ticket')
                        ->withErrors($validator)
                        ->withInput();
                }
            }

            $attachment = null;
            if ($request->hasfile('attachment')) {
                $file       = $request->file('attachment');
                $attachment = time() . $file->getClientOriginalName();
                $file->move(public_path() . "/uploads/media/", $attachment);
            }

            DB::beginTransaction();

            $supportticket                  = new SupportTicket();
            $supportticket->user_id         = auth()->id();
            $supportticket->subject         = $request->input('subject');
            $supportticket->status          = 0;
            $supportticket->created_user_id = auth()->id();

            $supportticket->save();

            $message             = new SupportMessage();
            $message->message    = $request->message;
            $message->sender_id  = $supportticket->user_id;
            $message->attachment = $attachment;

            $supportticket->messages()->save($message);

            DB::commit();

            return redirect()->route('tickets.my_tickets', ['status' => 'pending'])->with('success', _lang('New ticket created successfully'));

        }
    }

    /**
     * Reply Message
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reply(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'message'    => 'required',
            'attachment' => 'nullable|mimes:jpeg,jpg,png,doc,pdf,docx,zip',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $supportticket = SupportTicket::where('id', $id)->where('status', '!=', 2)->where('user_id', auth()->id())->first();

        $attachment = null;
        if ($request->hasfile('attachment')) {
            $file       = $request->file('attachment');
            $attachment = time() . $file->getClientOriginalName();
            $file->move(public_path() . "/uploads/media/", $attachment);
        }

        $message             = new SupportMessage();
        $message->message    = $request->message;
        $message->sender_id  = auth()->id();
        $message->attachment = $attachment;

        $supportticket->messages()->save($message);

        return back()->with('success', _lang('Ticket Replied'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        $supportticket = SupportTicket::where('id', $id)->where('user_id', auth()->id())->first();
        return view('backend.customer_portal.support_ticket.view', compact('supportticket', 'id'));
    }

    /**
     * Closed the specified ticket.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mark_as_closed($id) {
        $supportticket                 = SupportTicket::where('id', $id)->where('user_id', auth()->id())->where('status', 1)->first();
        $supportticket->status         = 2;
        $supportticket->closed_user_id = auth()->id();
        $supportticket->save();

        return redirect()->route('tickets.my_tickets', ['status' => 'closed'])->with('success', _lang('Ticket Closed'));
    }

}