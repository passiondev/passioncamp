<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>PCC Students SMMR CMP</title>

    <!-- Styles -->
    <link href="{{ asset('css/front.css') }}" rel="stylesheet">

    @yield('head')
</head>
<body id="front-layout">
    <div class="container">
        <header id="front-header" style="margin-bottom:60px">
            <img src="{{ asset('img/header.jpg') }}" alt="PCC Students SMMR CMP" class="img-responsive">
        </header>
        @yield('content')
    </div>
</div>

    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.2.7/jquery.inputmask.bundle.min.js"></script>
    <script src={{ asset('js/app.js') }}></script>
    @yield('foot')
</body>
</html>
