<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Passion Camp</title>

    <!-- Styles -->
    <link href="{{ asset('semantic/dist/semantic.min.css') }}" rel="stylesheet">
    <link href="http://code.jquery.com/ui/1.11.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('head')
</head>
<body id="app-layout" class="roominglist">

    <!-- Sidebar Menu -->
    <div class="ui left large vertical inverted pointing sidebar menu">
        @include ('menu.left')
        <div class="ui divider"></div>
        <div class="menu">
            @include ('menu.right')
        </div>        
    </div>

    <div class="pusher">

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
