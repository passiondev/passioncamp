<!doctype html>
<html>
<head>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, Helvetica, sans-serif;
            text-align: center;
        }
        .container {
            width: 2in;
            height: 3in;
            margin: 0 auto;
            position: relative;
        }
        .dont-break-out {

            /* These are technically the same, but use both */
            overflow-wrap: break-word;
            word-wrap: break-word;
            -ms-word-break: break-all;
            /* This is the dangerous one in WebKit, as it breaks things wherever */
            word-break: break-all;
            /* Instead use this non-standard one: */
            word-break: break-word;
            /* Adds a hyphen where the word breaks, if supported (No Blink) */
            -ms-hyphens: auto;
            -moz-hyphens: auto;
            -webkit-hyphens: auto;
            hyphens: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <p style="width:100%;">
            <img src="{{ url('img/room/label/icon.png') }}" style="width:80%;max-width:200px;">
        </p>

        <p>{{ $room->organization->church->name }}</p>

        <div>
            @if ($room->roomnumber)
                {{ $room->roomnumber }}
            @else
                <div style="border-bottom:1px solid #000000;width:45%;margin:0 auto">&nbsp;</div>
            @endif
        </div>

        <br>

        <div>
            @foreach ($room->tickets->assigendSort() as $ticket)
                {{ $ticket->person->name }}
                @if ($ticket->squad)
                    <i>{{ strtoupper($ticket->squad) }}</i>
                @endif
                <br>
            @endforeach
        </div>

        <div style="position:absolute;bottom:0;left:0;right:0;">
            {{ $room->name }}
        </div>
    </div>
</body>
</html>
