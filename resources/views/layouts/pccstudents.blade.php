<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Passion Students - ' . $occurrence->title)</title>

    <!-- Styles -->
    <link href="{{ asset(mix('/css/bootstrap4.css')) }}" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.store = {};
    </script>
    <script src="//d2wy8f7a9ursnm.cloudfront.net/v4/bugsnag.min.js"></script>
    <script>window.bugsnagClient = bugsnag(@json(config('bugsnag.api_key')))</script>
    @yield('head')
</head>
<body style="padding-top:0">
    <header style="background-color: black">
        <div class="container">
            <img data-src="https://res.cloudinary.com/pcc/image/upload/f_auto,q_auto,w_1200/v1551727604/students_registration/passioncamp2019/header-big.jpg" alt="{{ $occurrence->title }}" class="cld-responsive img-fluid">
        </div>
    </header>
    <div id="page-header-banner" class="py-2">
        <div class="container d-flex flex-column align-items-center justify-content-between flex-md-row text-center">
            <img data-src="https://res.cloudinary.com/pcc/image/upload/w_auto,dpr_auto,f_auto,q_auto/v1541435334/students_registration/logo.png" class="cld-responsive" alt="PCC Students" style="width:252px;">
            <h1>{{ $occurrence->title }}</h1>
        </div>
    </div>
    <div id="app">
        @unless (Auth::guest())
            <nav class="navbar navbar-toggleable-md navbar-light bg-white mb-3">
                <div class="container">
                    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        PCC Students
                    </a>

                    <div class="collapse navbar-collapse" id="app-navbar-collapse">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav mr-auto">
                            @unless(Auth::guest())
                            @endif
                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav">
                            <!-- Authentication Links -->
                            @if (Auth::guest())
                                <li class="nav-item"><a class="nav-link" href="{{ url('/login') }}">Login</a></li>
                            @else
                                <li class="nav-item dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                        {{ Auth::user()->name }} <span class="caret"></span>
                                    </a>

                                    <ul class="dropdown-menu" role="menu">
                                        <li class="dropdown-item">
                                            <a href="{{ url('/logout') }}"
                                                onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                                Logout
                                            </a>

                                            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </nav>
        @endunless

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cloudinary-core/2.5.0/cloudinary-core-shrinkwrap.min.js"></script>
    <script type="text/javascript">
        var cl = cloudinary.Cloudinary.new({cloud_name: "pcc"});
        cl.responsive();
    </script>
    @yield('foot')
    <script src="{{ asset(mix('/js/app.js')) }}"></script>
    {{ svg_spritesheet() }}
</body>
</html>
