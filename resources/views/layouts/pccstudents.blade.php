<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>PCC Students SMMR CMP</title>

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
<body style="padding-top:0">
    <header id="page-header" class="text-center pt-xl-5">
        <img src="{{ request('code') ? '/img/header-content-special.png' : '/img/header-content.png' }}" alt="SMMR CMP" class="img-fluid">
    </header>
    <div id="page-header-banner">
        <div class="container d-flex flex-column align-items-center justify-content-between flex-sm-row">
            <img src="/img/logo.png" alt="PCC Students" style="height: 40px;width:252px;">
            <h1>SMMR CMP</h1>
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
                        PCC Students SMMR CMP
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
                                                {{ csrf_field() }}
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
    @yield('foot')
    <script src="{{ mix('/js/app.js') }}"></script>
    {{ svg_spritesheet() }}
</body>
</html>
