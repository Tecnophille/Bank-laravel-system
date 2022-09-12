<?php

namespace App\Http\Controllers;

use App\Mail\GeneralMail;
use App\Models\User;
use App\Utilities\Overrider;
use DataTables;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Twilio\Rest\Client;

class UserController extends Controller {

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
    public function index($status = 'all') {
        return view('backend.user.list', compact('status'));
    }

    public function get_table_data($status = 'all') {

        $users = User::select('users.*')
            ->with('branch')
            ->where('user_type', 'customer')
            ->when($status, function ($query, $status) {
                if ($status == 'email_verified') {
                    return $query->where('email_verified_at', '!=', null);
                } else if ($status == 'sms_verified') {
                    return $query->where('sms_verified_at', '!=', null);
                } else if ($status == 'email_unverified') {
                    return $query->where('email_verified_at', null);
                } else if ($status == 'sms_unverified') {
                    return $query->where('sms_verified_at', null);
                } else if ($status == 'inactive') {
                    return $query->where('status', 0);
                } else if ($status == 'active') {
                    return $query->where('status', 1);
                }
            })
            ->orderBy("users.id", "desc");

        return Datatables::eloquent($users)
            ->editColumn('phone', function ($user) {
                return '+' . $user->country_code . '-' . $user->phone;
            })
            ->editColumn('status', function ($user) {
                return status($user->status);
            })
            ->addColumn('profile_picture', function ($user) {
                return '<img class="thumb-sm img-thumbnail"
				src="' . profile_picture($user->profile_picture) . '">';
            })
            ->editColumn('email_verified_at', function ($user) {
                return $user->email_verified_at != null ? show_status(_lang('Yes'), 'primary') : show_status(_lang('No'), 'danger');
            })
            ->editColumn('sms_verified_at', function ($user) {
                return $user->sms_verified_at != null ? show_status(_lang('Yes'), 'primary') : show_status(_lang('No'), 'danger');
            })
            ->addColumn('action', function ($user) {
                return '<div class="text-center"><form action="' . action('UserController@destroy', $user['id']) . '" class="text-center" method="post">'
                . '<a href="' . action('UserController@show', $user['id']) . '" class="btn btn-primary btn-sm"><i class="icofont-eye-alt"></i></a>&nbsp;'
                . '<a href="' . action('UserController@edit', $user['id']) . '" data-title="' . _lang('Update User') . '" class="btn btn-warning btn-sm ajax-modal"><i class="icofont-ui-edit"></i></a>&nbsp;'
                . csrf_field()
                    . '<input name="_method" type="hidden" value="DELETE">'
                    . '<button class="btn btn-danger btn-sm btn-remove" type="submit"><i class="icofont-trash"></i></button>'
                    . '</form></div>';
            })
            ->setRowId(function ($user) {
                return "row_" . $user->id;
            })
            ->rawColumns(['status', 'profile_picture', 'email_verified_at', 'sms_verified_at', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        if (!$request->ajax()) {
            return view('backend.user.create');
        } else {
            return view('backend.user.modal.create');
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
            'name'            => 'required|max:255',
            'email'           => 'required|email|unique:users|max:255',
            'branch_id'       => 'required',
            'status'          => 'required',
            'profile_picture' => 'nullable|image',
            'password'        => 'required|min:6',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('users.create')
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        $profile_picture = "";
        if ($request->hasfile('profile_picture')) {
            $file            = $request->file('profile_picture');
            $profile_picture = time() . $file->getClientOriginalName();
            $file->move(public_path() . "/uploads/profile/", $profile_picture);
        }

        $user                    = new User();
        $user->name              = $request->input('name');
        $user->email             = $request->input('email');
        $user->country_code      = $request->input('country_code');
        $user->phone             = $request->input('phone');
        $user->user_type         = 'customer';
        $user->branch_id         = $request->branch_id;
        $user->status            = $request->input('status');
        $user->profile_picture   = $profile_picture;
        $user->email_verified_at = $request->email_verified_at;
        $user->sms_verified_at   = $request->sms_verified_at;
        $user->password          = Hash::make($request->password);

        $user->save();

        //Prefix Output
        $user->status          = status($user->status);
        $user->branch_id       = $user->branch->name;
        $user->profile_picture = '<img src="' . profile_picture($user->profile_picture) . '" class="thumb-sm img-thumbnail">';

        if (!$request->ajax()) {
            return redirect()->route('users.create')->with('success', _lang('Saved successfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'store', 'message' => _lang('Saved successfully'), 'data' => $user, 'table' => '#users_table']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        $user = User::find($id);
        if (!$request->ajax()) {
            return view('backend.user.view', compact('user', 'id'));
        } else {
            return view('backend.user.modal.view', compact('user', 'id'));
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id) {
        $user = User::find($id);
        if (!$request->ajax()) {
            return view('backend.user.edit', compact('user', 'id'));
        } else {
            return view('backend.user.modal.edit', compact('user', 'id'));
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
            'name'            => 'required|max:255',
            'email'           => [
                'required',
                'email',
                Rule::unique('users')->ignore($id),
            ],
            'status'          => 'required',
            'branch_id'       => 'required',
            'profile_picture' => 'nullable|image',
            'password'        => 'nullable|min:6',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('users.edit', $id)
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        if ($request->hasfile('profile_picture')) {
            $file            = $request->file('profile_picture');
            $profile_picture = time() . $file->getClientOriginalName();
            $file->move(public_path() . "/uploads/profile/", $profile_picture);
        }

        $user               = User::find($id);
        $user->name         = $request->input('name');
        $user->email        = $request->input('email');
        $user->country_code = $request->input('country_code');
        $user->phone        = $request->input('phone');
        $user->status       = $request->input('status');
        $user->branch_id    = $request->branch_id;
        if ($request->hasfile('profile_picture')) {
            $user->profile_picture = $profile_picture;
        }
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->email_verified_at = $request->email_verified_at;
        $user->sms_verified_at   = $request->sms_verified_at;

        $user->save();

        //Prefix Output
        $user->status          = status($user->status);
        $user->branch_id       = $user->branch->name;
        $user->profile_picture = '<img src="' . profile_picture($user->profile_picture) . '" class="thumb-sm img-thumbnail">';

        if (!$request->ajax()) {
            return redirect()->route('users.index')->with('success', _lang('Updated successfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'update', 'message' => _lang('Updated successfully'), 'data' => $user, 'table' => '#users_table']);
        }

    }

    public function send_email(Request $request) {
        @ini_set('max_execution_time', 0);
        @set_time_limit(0);

        Overrider::load("Settings");

        $validator = Validator::make($request->all(), [
            'user_email' => 'required',
            'subject'    => 'required',
            'message'    => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return back()->withErrors($validator)
                    ->withInput();
            }
        }

        //Send email
        $subject = $request->input("subject");
        $message = $request->input("message");

        $mail          = new \stdClass();
        $mail->subject = $subject;
        $mail->body    = $message;

        try {
            Mail::to($request->user_email)
                ->send(new GeneralMail($mail));
        } catch (\Exception $e) {
            if (!$request->ajax()) {
                return back()->with('error', _lang('Sorry, Error Occured !'));
            } else {
                return response()->json(['result' => 'error', 'message' => _lang('Sorry, Error Occured !')]);
            }
        }

        if (!$request->ajax()) {
            return back()->with('success', _lang('Email Send Sucessfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'update', 'message' => _lang('Email Send Sucessfully'), 'data' => $contact]);
        }
    }

    public function send_sms(Request $request) {
        @ini_set('max_execution_time', 0);
        @set_time_limit(0);

        Overrider::load("Settings");

        $validator = Validator::make($request->all(), [
            'phone'   => 'required',
            'message' => 'required:max:160',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return back()->withErrors($validator)
                    ->withInput();
            }
        }

        //Send message
        $message = $request->input("message");

        if (get_option('enable_sms') == 0) {
            return back()->with('error', _lang('Sorry, SMS not enabled !'));
        }

        $account_sid   = get_option('twilio_account_sid');
        $auth_token    = get_option('twilio_auth_token');
        $twilio_number = get_option('twilio_mobile_number');
        $client        = new Client($account_sid, $auth_token);

        try {
            $client->messages->create($request->phone,
                ['from' => $twilio_number, 'body' => $message]);
        } catch (\Exception $e) {
            if (!$request->ajax()) {
                return back()->with('error', _lang('Sorry, Error Occured !'));
            } else {
                return response()->json(['result' => 'error', 'message' => _lang('Sorry, Error Occured !')]);
            }
        }

        if (!$request->ajax()) {
            return back()->with('success', _lang('SMS Send Sucessfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'update', 'message' => _lang('SMS Send Sucessfully'), 'data' => $contact]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', _lang('Deleted successfully'));
    }
}