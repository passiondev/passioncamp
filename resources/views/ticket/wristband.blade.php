<!doctype html>
<html>
<head>
    <style>
        @page {
            size: 12in 1.5in;
            margin: .25in;
            margin-bottom: 0;
        }
        * {
            -webkit-box-sizing: border-box;
                  box-sizing: border-box;
        }
        body {
            font-size: 12pt;
            font-family: 'Helvetica Neue', Arial, Helvetica, sans-serif;
        }
        .container {
        }
        table {
            margin: 0;
            border-collapse: collapse;
            border: 0;
        }
        td {
            vertical-align: top;
        }
    </style>
</head>
<body>
    <div class="container">
        <table style="height:1in;">
            <tbody>
                <tr>
                    <td style="padding: .25in">
                        <img src="{{ public_path('img/ticket/wristband/icon.png') }}" style="height:.5in">
                    </td>
                    <td style="padding: 0 .25in; vertical-align:middle">
                        <strong>{{ $ticket->person->name }}</strong> <br>
                        @if(!!$ticket->person->grade) {{ number_ordinal($ticket->person->grade) }} Grade <br> @endif
                        @if(!!$ticket->squad) {{ $ticket->squad }} <br> @endif
                        @if(!!$ticket->leader) {{ $ticket->leader }} <br> @endif
                        @if(!!$ticket->bus) Bus #{{ $ticket->bus }} @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="container">
        <table style="height:1in;">
            <tbody>
                <tr>
                    <td style="padding: .25in">
                        <img src="{{ public_path('img/ticket/wristband/icon.png') }}" style="height:.5in">
                    </td>
                    <td style="padding: 0 .25in; vertical-align:middle">
                        <strong>{{ $ticket->person->name }}</strong> <br>
                        @if(!!$ticket->person->grade) {{ number_ordinal($ticket->person->grade) }} Grade <br> @endif
                        @if(!!$ticket->squad) {{ $ticket->squad }} <br> @endif
                        @if(!!$ticket->leader) {{ $ticket->leader }} <br> @endif
                        @if(!!$ticket->bus) Bus #{{ $ticket->bus }} @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
