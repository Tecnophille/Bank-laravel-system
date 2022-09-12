<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Mail\ContactUs;
use App\Models\Faq;
use App\Models\FDRPlan;
use App\Models\LoanProduct;
use App\Models\Page;
use App\Models\Service;
use App\Models\Team;
use App\Models\Testimonial;
use App\Utilities\Overrider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class WebsiteController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {

        if (env('APP_INSTALLED', true) == true) {
            date_default_timezone_set(get_option('timezone', 'Asia/Dhaka'));
            $this->middleware(function ($request, $next) {
                if (get_option('website_enable', 'yes') == 'no') {
                    return redirect()->route('login');
                }
                if (isset($_GET['language'])) {
                    session(['language' => $_GET['language']]);
                    return back();
                }
                return $next($request);
            });
        }
    }

    /**
     * Display website's home page
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug = '') {
        $data = array();

        if ($slug != '') {
            $page = Page::where('slug', $slug)->where('status', 1)->first();
            if (!$page) {
                abort(404);
            }
            return view('theme.page', compact('page'));
        }

        $data['services']     = Service::all();
        $data['testimonials'] = Testimonial::all();
        $data['fdr_plans']    = FDRPlan::where('status', 1)
            ->get();
        $data['loan_plans'] = LoanProduct::where('status', 1)
            ->get();
        return view('theme.index', $data);
    }

    public function about() {
        $data                 = array();
        $data['team_members'] = Team::all();
        return view('theme.about', $data);
    }

    public function services() {
        $data             = array();
        $data['services'] = Service::all();
        return view('theme.services', $data);
    }

    public function faq() {
        $data         = array();
        $data['faqs'] = Faq::where('status', 1)->get();
        return view('theme.faq', $data);
    }

    public function contact() {
        return view('theme.contact');
    }

    public function send_message(Request $request) {
        @ini_set('max_execution_time', 0);
        @set_time_limit(0);

        Overrider::load("Settings");

        $this->validate($request, [
            'name'    => 'required',
            'email'   => 'required|email',
            'phone'   => 'required',
            'subject' => 'required',
            'message' => 'required',
        ]);

        //Send Email
        $name    = $request->input("name");
        $email   = $request->input("email");
        $phone   = $request->input("phone");
        $subject = $request->input("subject");
        $message = $request->input("message");

        $mail          = new \stdClass();
        $mail->name    = $name;
        $mail->email   = $email;
        $mail->phone   = $phone;
        $mail->subject = $subject;
        $mail->message = $message;

        if (get_option('email') != '') {
            try {
                Mail::to(get_option('email'))->send(new ContactUs($mail));
                return back()->with('success', _lang('Your Message send sucessfully.'));
            } catch (\Exception $e) {
                return back()->with('error', _lang('Error Occured, Please try again !'));
            }
        }

    }

}