<?php

namespace App\Http\Controllers;

use App\Models\SupportMessage;
use App\Models\SupportTicket;
use DataTables;
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
    public function index(Request $request) {
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

        return view('backend.support_ticket.list', compact('status', 'title'));
    }

    public function get_table_data($status = 1) {
        $permissions['assignStaffPermission']  = has_permission('support_tickets.assign_staff');
        $permissions['markAsClosedPermission'] = has_permission('support_tickets.mark_as_closed');
        $permissions['showPermission']         = has_permission('support_tickets.show');
        $permissions['replyPermission']        = has_permission('support_tickets.reply');

        $supporttickets = SupportTicket::select('support_tickets.*')
            ->where('status', $status)
            ->with(['user', 'created_by'])
            ->orderBy("support_tickets.id", "desc");

        return Datatables::eloquent($supporttickets)
            ->addColumn('status', function ($supportticket) {
                return ticket_status($supportticket->status);
            })
            ->addColumn('action', function ($supportticket) use ($permissions) {
                if (auth()->user()->user_type == 'admin') {
                    $actions = '<form action="' . action('SupportTicketController@destroy', $supportticket['id']) . '" class="text-center" method="post">';
                    $actions .= $supportticket->status == 0 ? '<a href="' . action('SupportTicketController@show', $supportticket['id']) . '" class="btn btn-warning btn-sm"><i class="icofont-user-suited"></i> ' . _lang('Assign Staff') . '</a>&nbsp;' : '';
                    $actions .= $supportticket->status == 1 ? '<a href="' . action('SupportTicketController@show', $supportticket['id']) . '" class="btn btn-primary btn-sm"><i class="icofont-reply"></i> ' . _lang('Reply') . '</a>&nbsp;' : '';
                    $actions .= $supportticket->status == 2 ? '<a href="' . action('SupportTicketController@show', $supportticket['id']) . '" class="btn btn-primary btn-sm"><i class="icofont-ui-messaging"></i> ' . _lang('View Conversations') . '</a>&nbsp;' : '';
                    $actions .= $supportticket->status == 1 ? '<a href="' . route('support_tickets.mark_as_closed', $supportticket['id']) . '" class="btn btn-success btn-sm"><i class="icofont-check-circled"></i> ' . _lang('Mark as Closed') . '</a>&nbsp;' : '';
                    $actions .= csrf_field();
                    $actions .= '<input name="_method" type="hidden" value="DELETE">';
                    $actions .= '<button class="btn btn-danger btn-sm btn-remove" type="submit"><i class="icofont-trash"></i> ' . _lang('Delete') . '</button>';
                    $actions .= '</form>';
                } else {
                    $actions = '<div class="text-center">';
                    if ($permissions['assignStaffPermission']) {
                        $actions .= $supportticket->status == 0 ? '<a href="' . action('SupportTicketController@show', $supportticket['id']) . '" class="btn btn-warning btn-sm"><i class="icofont-user-suited"></i> ' . _lang('Assign Staff') . '</a>&nbsp;' : '';
                    }
                    if ($permissions['replyPermission']) {
                        $actions .= $supportticket->status == 1 ? '<a href="' . action('SupportTicketController@show', $supportticket['id']) . '" class="btn btn-primary btn-sm"><i class="icofont-reply"></i> ' . _lang('Reply') . '</a>&nbsp;' : '';
                    }

                    if ($permissions['showPermission']) {
                        $actions .= $supportticket->status == 2 ? '<a href="' . action('SupportTicketController@show', $supportticket['id']) . '" class="btn btn-primary btn-sm"><i class="icofont-ui-messaging"></i> ' . _lang('View Conversations') . '</a>&nbsp;' : '';
                    }

                    if ($permissions['markAsClosedPermission']) {
                        $actions .= $supportticket->status == 1 ? '<a href="' . route('support_tickets.mark_as_closed', $supportticket['id']) . '" class="btn btn-success btn-sm"><i class="icofont-check-circled"></i> ' . _lang('Mark as Closed') . '</a>&nbsp;' : '';
                    }
                    $action = '</div>';
                }

                return $actions;
            })
            ->setRowId(function ($supportticket) {
                return "row_" . $supportticket->id;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
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
            return view('backend.support_ticket.modal.create');
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
            'user_id'    => 'required',
            'subject'    => 'required',
            'message'    => 'required',
            'status'     => 'required',
            'attachment' => 'nullable|mimes:jpeg,JPEG,png,PNG,jpg,doc,pdf,docx,zip',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('support_tickets.create')
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
        $supportticket->user_id         = $request->input('user_id');
        $supportticket->subject         = $request->input('subject');
        $supportticket->status          = $request->input('status');
        $supportticket->created_user_id = auth()->id();

        $supportticket->save();

        $message             = new SupportMessage();
        $message->message    = $request->message;
        $message->sender_id  = $request->user_id;
        $message->attachment = $attachment;

        $supportticket->messages()->save($message);

        DB::commit();

        if (!$request->ajax()) {
            return redirect()->route('support_tickets.create')->with('success', _lang('New ticket created successfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'store', 'message' => _lang('New ticket created successfully'), 'data' => $supportticket, 'table' => '#support_tickets_table']);
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

        $supportticket = SupportTicket::where('id', $id)->where('status', 1)->first();
        if ($supportticket->status == 0) {
            $supportticket->status = 1;
            $supportticket->save();
        }

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
        $supportticket = SupportTicket::find($id);
        if (!$supportticket) {
            abort(404);
        }
        return view('backend.support_ticket.view', compact('supportticket', 'id'));
    }

    /**
     * Closed the specified ticket.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assign_staff($id, $userId) {
        $supportticket              = SupportTicket::where('id', $id)->where('status', 0)->first();
        $supportticket->status      = 1;
        $supportticket->operator_id = $userId;
        $supportticket->save();

        return redirect()->route('support_tickets.index', ['status' => 'active'])->with('success', _lang('Staff assigned sucessfully'));
    }

    /**
     * Closed the specified ticket.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mark_as_closed($id) {
        $supportticket                 = SupportTicket::where('id', $id)->where('status', 1)->first();
        $supportticket->status         = 2;
        $supportticket->closed_user_id = auth()->id();
        $supportticket->save();

        return redirect()->route('support_tickets.index', ['status' => 'closed'])->with('success', _lang('Ticket Closed'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if (auth()->user()->user_type != 'admin') {
            abort(403);
        }
        $supportticket = SupportTicket::find($id);
        $supportticket->delete();
        return redirect()->route('support_tickets.index')->with('success', _lang('Deleted Successfully'));
    }
}