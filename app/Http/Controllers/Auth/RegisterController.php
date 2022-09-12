<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Utilities\Overrider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
        Overrider::load("Settings");
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
        config(['recaptchav3.sitekey' => get_option('recaptcha_site_key')]);
        config(['recaptchav3.secret' => get_option('recaptcha_secret_key')]);

        return Validator::make($data, [
            'name'                 => ['required', 'string', 'max:191'],
            'email'                => ['required', 'string', 'email', 'max:191', 'unique:users'],
            'country_code'         => ['required'],
            'phone'                => ['required', 'string', 'unique:users'],
            'password'             => ['required', 'string', 'min:6', 'confirmed'],
            'g-recaptcha-response' => get_option('enable_recaptcha', 0) == 1 ? 'required|recaptchav3:register,0.5' : '',
        ], [
            'g-recaptcha-response.recaptchav3' => _lang('Recaptcha error!'),
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data) {
        return User::create([
            'name'            => $data['name'],
            'email'           => $data['email'],
            'country_code'    => $data['country_code'],
            'phone'           => $data['phone'],
            'password'        => Hash::make($data['password']),
            'user_type'       => 'customer',
            'status'          => 1,
            'profile_picture' => 'default.png',
        ]);
    }
}
