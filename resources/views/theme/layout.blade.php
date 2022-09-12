<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keywords" content="{{ get_option('meta_keywords','bank, online bank, send money') }}"/>
    <meta name="description" content="{{ get_option('meta_content','Online Banking Solutions') }}"/>

    <title>{{ get_option('site_title', config('app.name')) }}</title>

    <!-- Favicon-->
    <link rel="icon" type="image/png" href="{{ get_favicon() }}" />
    <!-- bootstrap.min css -->
    <link rel="stylesheet" href="{{ asset('public/theme/plugins/bootstrap/css/bootstrap.min.css') }}">
    <!-- Icon Font Css -->
    <link rel="stylesheet" href="{{ asset('public/theme/plugins/icofont/icofont.min.css') }}">
    <!-- Slick Slider  CSS -->
    <link rel="stylesheet" href="{{ asset('public/theme/plugins/slick-carousel/slick/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('public/theme/plugins/slick-carousel/slick/slick-theme.css') }}">

    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="{{ asset('public/theme/css/style.css') }}">
</head>

<body id="top">
    <header>
        <nav class="navbar navbar-expand-lg navigation" id="navbar">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ get_logo() }}" alt="" class="img-fluid">
                </a>

                <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarmain" aria-controls="navbarmain" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="icofont-navigation-menu"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarmain">
                    {!! xss_clean(show_navigation(get_option('primary_menu'), 'navbar-nav ml-auto', 'nav-link')) !!}

                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item"><a class="nav-link btn-outline-red mr-lg-2" href="{{ route('login') }}"><i class="icofont-lock"></i> Sign In</a></li>
                        @if(get_option('allow_singup') == 'yes')
                        <li class="nav-item"><a class="nav-link btn-signup mr-lg-2" href="{{ route('register') }}"><i class="icofont-ui-user"></i> Sign Up</a></li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle btn-outline-red" id="languageSelector" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="icofont-globe"></i>  {{ session('language') =='' ? get_option('language') : session('language') }} <i class="icofont-thin-down"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageSelector">
                                @foreach(get_language_list() as $language)
                                    <a class="dropdown-item" href="{{ url('/') }}?language={{ $language }}">{{ $language }}</a>
                                @endforeach
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    @yield('content')

    <!-- footer Start -->
    <footer class="footer section gray-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mr-auto col-sm-12">
                    <div class="widget mb-5 mb-lg-0">
                        <div class="logo mb-4">
                            <img src="{{ get_logo() }}" alt="" class="img-fluid">
                        </div>
                        <p>{{ get_trans_option('footer_about_us') }}</p>

                        <ul class="list-inline footer-socials mt-4">
                            <li class="list-inline-item"><a href="{{ get_option('facebook_link') }}"><i class="icofont-facebook"></i></a></li>
                            <li class="list-inline-item"><a href="{{ get_option('twitter_link') }}"><i class="icofont-twitter"></i></a></li>
                            <li class="list-inline-item"><a href="{{ get_option('linkedin_link') }}"><i class="icofont-linkedin"></i></a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 col-sm-6">
                    <div class="widget mb-5 mb-lg-0">
                        <h4 class="text-capitalize mb-3">{{ get_option('footer_menu_1_title') }}</h4>
                        <div class="divider mb-4"></div>
                        {!! xss_clean(show_navigation(get_option('footer_menu_1'), 'list-unstyled footer-menu lh-35')) !!}
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 col-sm-6">
                    <div class="widget mb-5 mb-lg-0">
                        <h4 class="text-capitalize mb-3">{{ get_option('footer_menu_2_title') }}</h4>
                        <div class="divider mb-4"></div>
                        {!! xss_clean(show_navigation(get_option('footer_menu_2'), 'list-unstyled footer-menu lh-35')) !!}
                    </div>
                </div>
            </div>

            <div class="footer-btm py-4 mt-5">
                <div class="row align-items-center justify-content-between">
                    <div class="col-lg-12">
                        <div class="copyright">
                            {!! xss_clean(get_trans_option('copyright')) !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4">
                        <a class="backtop js-scroll-trigger" href="#top">
                            <i class="icofont-long-arrow-up"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>


    <!-- Main jQuery -->
    <script src="{{ asset('public/theme/plugins/jquery/jquery.js') }}"></script>
    <!-- Bootstrap 4.3.2 -->
    <script src="{{ asset('public/theme/plugins/bootstrap/js/popper.js') }}"></script>
    <script src="{{ asset('public/theme/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- Slick Slider -->
    <script src="{{ asset('public/theme/plugins/slick-carousel/slick/slick.min.js') }}"></script>
    <!-- Counterup -->
    <script src="{{ asset('public/theme/plugins/counterup/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('public/theme/plugins/counterup/jquery.counterup.min.js') }}"></script>

    <script src="{{ asset('public/theme/js/script.js') }}"></script>

	@yield('js-script')
</body>
</html>
