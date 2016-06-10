<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Church</th>
                    <th>Current Name</th>
                    <th>Current Description</th>
                    <th>Current Notes</th>
                    <th>Previous Name</th>
                    <th>Previous Description</th>
                    <th>Previous Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($changed_rooms as $room)
                    <tr>
                        <td>{{ $room['id'] }}</td>
                        <td>{{ $room['church'] }}</td>
                        <td>{{ $room['current']['name'] }}</td>
                        <td>{{ $room['current']['desc'] }}</td>
                        <td>{{ $room['current']['notes'] }}</td>
                        <td>{{ $room['previous']['name'] }}</td>
                        <td>{{ $room['previous']['desc'] }}</td>
                        <td>{{ $room['previous']['notes'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>