<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Image;
use Twilio\Rest\Client;
use Validator;

class ProfileController extends Controller {
    public function __construct() {
        date_default_timezone_set(get_option('timezone', 'Asia/Dhaka'));
    }

    public function index() {
        $alert_col = 'col-lg-8 offset-lg-2';
        $profile   = User::find(Auth::User()->id);
        return view('backend.profile.profile_view', compact('profile', 'alert_col'));
    }

    public function edit() {
        $alert_col = 'col-lg-8 offset-lg-2';
        $profile   = User::find(Auth::User()->id);
        return view('backend.profile.profile_edit', compact('profile', 'alert_col'));
    }

    public function show_notification($id) {
        $notification = auth()->user()->notifications()->find($id);
        if ($notification && request()->ajax()) {
            $notification->markAsRead();
            return new Response('<div class="alert alert-info" role="alert">' . $notification->data['message'] . '</div>');
        }
        return back();
    }

    public function notification_mark_as_read($id) {
        $notification = auth()->user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
    }

    public function update(Request $request) {
        $this->validate($request, [
            'name'            => 'required',
            'country_code'    => 'required',
            'phone'           => 'required',
            'email'           => [
                'required',
                Rule::unique('users')->ignore(Auth::User()->id),
            ],
            'profile_picture' => 'nullable|image|max:5120',
        ]);

        DB::beginTransaction();

        $profile               = Auth::user();
        $profile->name         = $request->name;
        $profile->email        = $request->email;
        $profile->country_code = $request->country_code;
        $profile->phone        = $request->phone;
        if ($request->hasFile('profile_picture')) {
            $image     = $request->file('profile_picture');
            $file_name = "profile_" . time() . '.' . $image->getClientOriginalExtension();
            Image::make($image)->crop(300, 300)->save(base_path('public/uploads/profile/') . $file_name);
            $profile->profile_picture = $file_name;
        }

        $profile->save();

        DB::commit();

        return redirect()->route('profile.index')->with('success', _lang('Profile updated successfully'));
    }

    /**
     * Show the form for change_password the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function change_password() {
        $alert_col = 'col-lg-8 offset-lg-2';
        return view('backend.profile.change_password', compact('alert_col'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_password(Request $request) {
        $this->validate($request, [
            'oldpassword' => 'required',
            'password'    => 'required|string|min:6|confirmed',
        ]);

        $user = User::find(Auth::User()->id);
        if (Hash::check($request->oldpassword, $user->password)) {
            $user->password = Hash::make($request->password);
            $user->save();
        } else {
            return back()->with('error', _lang('Old Password did not match !'));
        }
        return back()->with('success', _lang('Password has been changed'));
    }

    public function mobile_verification(Request $request) {
        if (request()->isMethod('get')) {
            if (get_option('mobile_verification') == 'enabled' && auth()->user()->sms_verified_at == null && auth()->user()->user_type == 'customer') {

                //Send Verification Code
                $account_sid   = get_option('twilio_account_sid');
                $auth_token    = get_option('twilio_auth_token');
                $twilio_number = get_option('twilio_mobile_number');
                $client        = new Client($account_sid, $auth_token);

                $code      = random_int(100000, 999999);
                $body      = "Please use this code - $code to verify your phone number";
                $api_error = '';

                try {
                    $client->messages->create('+' . auth()->user()->country_code . auth()->user()->phone,
                        ['from' => $twilio_number, 'body' => $body]);
                } catch (\Exception $e) {
                    $api_error = 'SMS API HAVING ISSUES. PLESE CHECK YOUR SMS CONFIGURATION !';
                }

                $alert_col = 'col-lg-6 offset-lg-3';
                $sms_token = encrypt($code);
                return view('backend.profile.mobile_verification', compact('alert_col', 'api_error', 'sms_token'));
            }
            return back();
        } else if (request()->isMethod('post')) {

            $validator = Validator::make($request->all(), [
                'verification_code' => 'required',
                'sms_token'         => 'required',
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
                } else {
                    return back()->withErrors($validator)
                        ->withInput();
                }
            }

            if (request()->verification_code == decrypt(request()->sms_token)) {
                $profile                  = Auth::user();
                $profile->sms_verified_at = now();
                $profile->save();

                if (!$request->ajax()) {
                    return redirect()->route('dashboard.index')->with('success', _lang('Verification Successfully'));
                } else {
                    return response()->json(['result' => 'success', 'message' => _lang('Verification Successfully')]);
                }
            } else {
                if (!$request->ajax()) {
                    return back()->with('error', _lang('Invalid Verification Code !'));
                } else {
                    return response()->json(['result' => 'error', 'action' => 'store', 'message' => _lang('Invalid Verification Code !')]);
                }
            }

        }
    }

}
