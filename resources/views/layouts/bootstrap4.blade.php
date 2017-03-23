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
    @yield('head')
</head>
<body>
    <nav class="navbar navbar-toggleable-md navbar-light mb-3 fixed-top bg-faded">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#app-navbar-collapse">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Branding Image -->
        <a class="navbar-brand py-0" href="{{ url('/') }}">
            <img src="{{ url('img/camp-logo.png') }}" alt="Passion Camp" height="40">
        </a>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav">
                @if (Session::has('spark:impersonator'))
                    <li class="nav-item"><a class="nav-link" href="{{ action('Auth\ImpersonationController@stopImpersonating') }}">End Impersonation</a></li>
                @endif
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li class="nav-item"><a class="nav-link" href="{{ url('/login') }}">Login</a></li>
                @else
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->person->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
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
    <div class="container-fluid">
        @if (Auth::check())
            <div class="row">
                <div class="col-sm-3 col-md-2 py-3 bg-faded sidebar">
                    @unless (Auth::user()->isSuperAdmin())
                        <h4 class="px-sm-3 mb-3">{{ auth()->user()->organization->church->name }}</h4>
                    @endunless
                    <ul class="nav nav-pills flex-sm-column">
                        @if (Auth::user()->isSuperAdmin())
                            <li class="nav-item">
                                <a href="{{ action('Super\DashboardController') }}" class="nav-link {{ str_contains(Request::route()->getActionName(), 'Super\DashboardController') ? 'active' :'' }}">
                                    Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ action('OrganizationController@index') }}" class="nav-link {{ str_contains(Request::route()->getActionName(), 'OrganizationController') ? 'active' :'' }}">
                                    Churches
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ action('TicketController@index') }}" class="nav-link {{ str_contains(Request::route()->getActionName(), 'TicketController') ? 'active' :'' }}">
                                    Attendees
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ action('HotelController@index') }}" class="nav-link {{ str_contains(Request::route()->getActionName(), 'HotelController') ? 'active' :'' }}">
                                    Hotels
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ action('Super\UserController@index') }}" class="nav-link {{ str_contains(Request::route()->getActionName(), 'Super\UserController') ? 'active' :'' }}">
                                    Users
                                </a>
                            </li>
                        @endif
                        @if (Auth::user()->isChurchAdmin())
                            <li class="nav-item">
                                <a href="{{ action('Account\DashboardController') }}" class="nav-link {{ str_contains(Request::route()->getActionName(), 'Account\DashboardController') ? 'active' :'' }}">
                                    Account
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ action('TicketController@index') }}" class="nav-link {{ str_contains(Request::route()->getActionName(), 'TicketController') ? 'active' :'' }}">
                                    Attendees
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ action('Account\SettingsController@index') }}" class="nav-link {{ str_contains(Request::route()->getActionName(), 'SettingsController') ? 'active' :'' }}">
                                    Settings
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
                <div class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3 pb-5" id="app">
                    @yield('content')
                </div>
            @else
                <div class="py-5">
                    @yield('content')
                </div>
            @endif
        </div>
    </div>
    <!-- Scripts -->
    @yield('foot')
    <script src="{{ mix('/js/app.js') }}"></script>
    {{ svg_spritesheet() }}
</body>
</html>
