<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Passion Camp</title>

    <!-- Styles -->
    <link href="{{ asset('semantic/dist/semantic.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('head')
</head>
<body id="app-layout" class="roominglist">

    <!-- Sidebar Menu -->
    <div class="ui left large vertical inverted pointing sidebar menu">
        <div class="ui divider"></div>
        <div class="menu">
        </div>        
    </div>

    <div class="pusher">
        <header class="ui secondary pointing large menu page-nav">
            <a class="toc item">
                <i class="sidebar icon"></i>
            </a>
            <div class="header item">
                Passion Camp
            </div>
            <div class="left menu">
            </div>
            <div class="right menu">
                @if (Session::has('spark:impersonator'))
                    <a class="item" href="{{ route('user.stop-impersonating') }}">End Impersonation</a>
                @endif

                <div class="mobile hidden">
                </div>

                <a class="item" title="Sign Out" href="{{ route('logout') }}">
                    <i class="sign out icon"></i>
                </a>
            </div>
        </header>

        @yield('content')
    </div>

    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.2.7/jquery.inputmask.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src={{ asset('semantic/dist/semantic.min.js') }}></script>
    <script src={{ asset('js/app.js') }}></script>
    @yield('foot')
</body>
</html>
