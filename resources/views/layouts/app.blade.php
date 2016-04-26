<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Passion Camp</title>

    <!-- Styles -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    @yield('head')
</head>
<body id="app-layout">

    <header class="top-bar" id="page-menu">
        <div class="top-bar-left">
            <ul class="menu vertical medium-horizontal">
                <li class="menu-text">Passion Camp</li>
                @if (auth()->user() && auth()->user()->is_super_admin)
                    <li><a href="{{ route('admin.organization.index') }}">Churches</a></li>
                    <li><a href="{{ route('hotel.index') }}">Hotels</a></li>
                    <li><a href="{{ route('user.index') }}">Users</a></li>
                @else
                    <li><a href="{{ route('account.dashboard') }}">Dashboard</a></li>
                @endif
                <li><a href="{{ route('order.index') }}">Registrations</a></li>
                <li><a href="{{ route('ticket.index') }}">Attendees</a></li>
                @unless (auth()->user() && auth()->user()->is_super_admin)
                    <li><a href="{{ route('account.settings') }}">Account</a></li>
                @endif
            </ul>
        </div>
        <div class="top-bar-right">
            <ul class="menu vertical medium-horizontal">
                @if (Session::has('spark:impersonator'))
                    <li><a href="{{ route('user.stop-impersonating') }}">End Impersonation</a></li>
                @endif
                <li><a href="{{ route('profile') }}">Profile</a></li>
            </ul>
        </div>
    </header>

    @yield('content')

    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.2.7/jquery.inputmask.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.2.1/foundation.min.js"></script>
    <script src={{ asset('js/app.js') }}></script>
    @yield('foot')
</body>
</html>
