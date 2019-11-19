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
        @foreach ($tickets as $ticket)
            <tr>
                <td>{{ $ticket['id'] }}</td>
                <td>{{ $ticket['church'] }}</td>
                <td>{{ Arr::get($ticket['current'], 'roomId') }}</td>
                <td>{{ Arr::get($ticket['current'], 'first_name') }}</td>
                <td>{{ Arr::get($ticket['current'], 'last_name') }}</td>
                <td>{{ Arr::get($ticket['previous'], 'roomId') }}</td>
                <td>{{ Arr::get($ticket['previous'], 'first_name') }}</td>
                <td>{{ Arr::get($ticket['previous'], 'last_name') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
