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
                    <th>Current Room ID</th>
                    <th>Current First Name</th>
                    <th>Current Last Name</th>
                    <th>Previous Room ID</th>
                    <th>Previous First Name</th>
                    <th>Previous Last Name</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($changed_tickets as $ticket)
                    <tr>
                        <td>{{ $ticket['id'] }}</td>
                        <td>{{ $ticket['church'] }}</td>
                        <td>{{ $ticket['current']['room_id'] }}</td>
                        <td>{{ $ticket['current']['fname'] }}</td>
                        <td>{{ $ticket['current']['lname'] }}</td>
                        <td>{{ $ticket['previous']['room_id'] }}</td>
                        <td>{{ $ticket['previous']['fname'] }}</td>
                        <td>{{ $ticket['previous']['lname'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>