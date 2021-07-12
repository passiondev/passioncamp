<!doctype html>
<html>
<head>
    <style>
        @page {
            size: 53mm 69mm;
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
        p {
            margin: 0 0 6pt 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="{{ public_path('img/room/label/icon_2021.png') }}" style="display:block;margin-top:6px;width:60%;max-width:200px;">

        <p>{{ $room->organization->church->name }}</p>

        @if ($room->roomnumber)
            <p style="font-size:12pt;">#<strong>{{ $room->roomnumber }}</strong></p>
        @else
            <p style="font-size:12pt;width:40%;margin:0 auto 4pt;border-bottom: 1px solid black">&nbsp;</p>
        @endif

        <div style="font-size:8pt;">
            @foreach ($room->tickets->sortBy('assigned_sort') as $ticket)
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
