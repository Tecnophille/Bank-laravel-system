<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Response;

class Customer {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (Auth::User()->user_type != 'customer') {
            if (!$request->ajax()) {
                return back()->with('error', _lang('Permission denied !'));
            } else {
                return new Response('<h5 class="text-center red">' . _lang('Permission denied !') . '</h5>');
            }
        }

        if (get_option('mobile_verification') == 'enabled' && Auth::User()->sms_verified_at == null) {
            return redirect()->route('profile.mobile_verification');
        }

        return $next($request);
    }
}
