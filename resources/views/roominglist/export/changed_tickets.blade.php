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
                    <th>Current Name</th>
                    <th>Previous Room ID</th>
                    <th>Previous Name</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket['id'] }}</td>
                        <td>{{ $ticket['church'] }}</td>
                        <td>{{ $ticket['current']['roomId'] }}</td>
                        <td>{{ $ticket['current']['name'] }}</td>
                        <td>{{ $ticket['previous']['roomId'] }}</td>
                        <td>{{ $ticket['previous']['name'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>
