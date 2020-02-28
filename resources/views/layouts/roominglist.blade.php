<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Passion Camp Portal</title>

    <!-- Styles -->
    <link href="{{ mix('/css/bootstrap4.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
        window.store = {};
    </script>
    <script src="//d2wy8f7a9ursnm.cloudfront.net/v4/bugsnag.min.js"></script>
    <script>window.bugsnagClient = bugsnag(@json(config('bugsnag.api_key')))</script>
    @yield('head')
</head>
<body>
    <nav class="navbar navbar-toggleable-md navbar-light mb-3 fixed-top bg-faded">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#app-navbar-collapse">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Branding Image -->
        <a class="navbar-brand py-0" href="{{ url('/') }}">
            <img src="https://res.cloudinary.com/passionconf/image/upload/c_scale,f_auto,h_80,q_auto/v1582922237/passioncamp2020/PCA20-001_Logo-White.png" alt="Passion Camp" height="40">
        </a>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                @if (Auth::user()->isChurchAdmin())
                    <li class="nav-item"><a class="nav-link" href="{{ action('Account\DashboardController') }}">&larr; Dashboard</a></li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ action('Super\DashboardController') }}">&larr; Dashboard</a></li>
                @endif
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav">
                @impersonating
                    <li class="nav-item"><a class="nav-link" href="{{ action('Auth\ImpersonationController@stopImpersonating') }}">End Impersonation</a></li>
                @endImpersonating
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li class="nav-item"><a class="nav-link" href="{{ url('/login') }}">Login</a></li>
                @else
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->person->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                            <li class="dropdown-item">
                                <a href="{{ action('ProfileController@show') }}">Profile</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </nav>
    <div class="container-fluid h-100 py-2" id="app">
        @yield('content')
    </div>
    <!-- Scripts -->
    @yield('foot')
    <script src="{{ mix('/js/app.js') }}"></script>
    {{ svg_spritesheet() }}
</body>
</html>
