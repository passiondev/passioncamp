<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Confirmation #</th>
                    <th>Church</th>
                    <th>Hotel</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Notes</th>
                    <th>Gender</th>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>First Name</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($all_rooms as $room)
                    <tr>
                        <td>{{ $room['id'] }}</td>
                        <td>{{ $room['confirmation_number'] }}</td>
                        <td>{{ $room['church'] }}</td>
                        <td @if($room['changed']) style="background-color: #FFFF00;" @endif>{{ $room['hotel'] }}</td>
                        <td @if($room['changed']) style="background-color: #FFFF00;" @endif>{{ $room['name'] }}</td>
                        <td @if($room['changed']) style="background-color: #FFFF00;" @endif>{{ $room['desc'] }}</td>
                        <td @if($room['changed']) style="background-color: #FFFF00;" @endif>{{ $room['notes'] }}</td>
                        <td>{{ $room['gender'] }}</td>
                        @foreach ($room['tickets'] as $ticket)
                            <td @if($ticket['changed']) style="background-color: #FFFF00;" @endif>{{ $ticket['lname'] }}</td>
                            <td @if($ticket['changed']) style="background-color: #FFFF00;" @endif>{{ $ticket['fname'] }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>