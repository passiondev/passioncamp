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
                    <th>First Name #1</th>
                    <th>Last Name #1</th>
                    <th>First Name #2</th>
                    <th>Last Name #2</th>
                    <th>First Name #3</th>
                    <th>Last Name #3</th>
                    <th>First Name #4</th>
                    <th>Last Name #4</th>
                    <th>First Name #5</th>
                    <th>Last Name #5</th>
                    <th>First Name #6</th>
                    <th>Last Name #6</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($allRooms as $room)
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
                            <td @if($ticket['changed']) style="background-color: #FFFF00;" @endif>{{ $ticket['first_name'] }}</td>
                            <td @if($ticket['changed']) style="background-color: #FFFF00;" @endif>{{ $ticket['last_name'] }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>
