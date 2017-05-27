<!doctype html>
<html>
<head>
    <style>
        @page {
            size: 51mm 52mm;
            margin: 0;
        }
        body {
            font-size: 10pt;
            font-family: 'Helvetica Neue', Arial, Helvetica, sans-serif;
            text-align: center;
        }
        .container {
            position: relative;
        }
        .fn {
            float: footnote;
        }
    </style>
</head>
<body>
    <div class="container">
        <p>
            <img src="{{ public_path('img/room/label/icon.png') }}" style="width:60%;max-width:200px;">
        </p>

        <p>{{ $room->organization->church->name }}</p>


        <br>

        <div style="font-size:8pt;">
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
