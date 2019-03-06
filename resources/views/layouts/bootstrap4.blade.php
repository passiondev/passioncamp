<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title.' | ' : '' }} {{ config('app.name') }}</title>

    <link href="{{ asset(mix('/css/bootstrap4.css')) }}" rel="stylesheet">

    <script>
        window.store = {};
    </script>

    @include('layouts._bugsnag')

    @yield('head')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-toggleable-md navbar-light mb-3 fixed-top bg-faded">
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand py-0 align-self-start align-self-md-auto" href="{{ url('/') }}">
                <img src="https://res.cloudinary.com/pcc/image/upload/h_24,dpr_2.0,f_auto,q_auto/v1541435334/students_registration/logo.png" alt="{{ config('app.name') }}" height="24">
            </a>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">
                    @if (Auth::check() && Auth::user()->isSuperAdmin())
                        <li class="nav-item">
                            {{-- <church-search></church-search> --}}
                        </li>
                    @endif
                    @if (Auth::check() && ! Auth::user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ action('User\DashboardController') }}">Dashboard</a>
                        </li>
                    @endif
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav">
                    @if (Session::has('spark:impersonator'))
                        <li class="nav-item"><a class="nav-link" href="{{ action('Auth\ImpersonationController@stopImpersonating') }}">End Impersonation</a></li>
                    @endif
                    @if (Session::has('printer'))
                        <li class="nav-item mr-3">
                            <a class="nav-link" href="{{ route('printers.index') }}">@icon('printer') {{ Session::get('printer.name') }}</a>
                        </li>
                    @elseif (str_contains(Request::route()->getActionName(), 'RoomController'))
                        <li class="nav-item mr-3">
                            <a class="btn btn-outline-primary" href="{{ route('printers.index') }}">Select a printer...</a>
                        </li>
                    @endif
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li class="nav-item"><a class="nav-link" href="{{ url('/login') }}">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('/admin') }}">Admin</a></li>
                    @else
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->person->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" role="menu">
                                <a class="dropdown-item" href="{{ action('ProfileController@show') }}">Profile</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Sign Out
                                </a>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>
        <div class="container-fluid">
            @if (Auth::check() && Auth::user()->isAdmin())
                <div class="row">
                    <div class="col-md-2 py-3 bg-faded sidebar">
                        @if (Auth::user()->isChurchAdmin())
                            <h4 class="px-sm-3 mb-3">{{ auth()->user()->organization->church->name }}</h4>
                        @endunless
                        <ul class="nav nav-pills flex-md-column">
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
                                    <a href="{{ route('tickets.index') }}" class="nav-link {{ str_contains(Request::route()->getActionName(), 'TicketController') ? 'active' :'' }}">
                                        Attendees
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ action('Super\RoominglistsController@index') }}" class="nav-link {{ str_contains(Request::route()->getActionName(), 'RoominglistsController') ? 'active' :'' }}">
                                        Rooming&nbsp;Lists
                                    </a>
                                </li>
                                @if (Route::has('waivers.index'))
                                    <li class="nav-item">
                                        <a href="{{ action('WaiverController@index') }}" class="nav-link {{ str_contains(Request::route()->getActionName(), 'WaiverController') ? 'active' :'' }}">
                                            Waivers
                                        </a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a href="{{ action('HotelController@index') }}" class="nav-link {{ str_contains(Request::route()->getActionName(), 'HotelController') ? 'active' :'' }}">
                                        Hotels
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ action('RoomController@index') }}" class="nav-link {{ str_contains(Request::route()->getActionName(), 'RoomController') ? 'active' :'' }}">
                                        Rooms
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('ticket-items.index') }}" class="nav-link {{ str_contains(Request::route()->getActionName(), 'TicketItemController') ? 'active' :'' }}">
                                        Tickets
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
                                    <a href="{{ route('tickets.index') }}" class="nav-link {{ str_contains(Request::route()->getActionName(), 'TicketController') ? 'active' :'' }}">
                                        Attendees
                                    </a>
                                </li>
                                @if (Route::has('roominglist.index') && auth()->user()->organization->hotelItems->count())
                                    <li class="nav-item">
                                        <a href="{{ action('RoomingListController@index') }}" class="nav-link {{ str_contains(Request::route()->getActionName(), 'RoomingListController') ? 'active' :'' }}">
                                            Rooming List
                                        </a>
                                    </li>
                                @endif
                                @if (Route::has('waivers.index'))
                                    <li class="nav-item">
                                        <a href="{{ route('waivers.index') }}" class="nav-link {{ str_contains(Request::route()->getActionName(), 'WaiverController') ? 'active' :'' }}">
                                            Waivers
                                        </a>
                                    </li>
                                @endif
                                @if (data_get(auth()->user(), 'organization.slug') == 'pcc')
                                    <li class="nav-item">
                                        <a href="{{ action('CheckinController@index') }}" class="nav-link {{ str_contains(Request::route()->getActionName(), 'CheckinController') ? 'active' :'' }}">
                                            Check In
                                        </a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a href="https://passion-forms.formstack.com/forms/passion_camp_2019_request" class="nav-link" target="_blank">
                                        Registration &amp; Hotel Requests
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('account.settings') }}" class="nav-link {{ str_contains(Request::route()->getActionName(), 'SettingsController') ? 'active' :'' }}">
                                        Settings
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-md-10 offset-md-2 pt-3 pb-5">
                        @yield('content')
                    </div>
                </div>
            @else
                <div class="py-5">
                    @yield('content')
                </div>
            @endif
        </div>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>

        <flash message="{{ session('flash') }}"></flash>
    </div>
    <!-- Scripts -->
    @yield('foot')
    <script src="{{ mix('/js/app.js') }}"></script>
    @stack('scripts')
    {{ svg_spritesheet() }}
</body>
</html>
