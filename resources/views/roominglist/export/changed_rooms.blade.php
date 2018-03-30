<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Church</th>
            <th>Current Hotel</th>
            <th>Current Name</th>
            <th>Current Description</th>
            <th>Current Notes</th>
            <th>Previous Hotel</th>
            <th>Previous Name</th>
            <th>Previous Description</th>
            <th>Previous Notes</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rooms as $room)
            <tr>
                <td>{{ $room['id'] }}</td>
                <td>{{ $room['church'] }}</td>
                <td>{{ $room['current']['hotelName'] }}</td>
                <td>{{ $room['current']['name'] }}</td>
                <td>{{ $room['current']['description'] }}</td>
                <td>{{ $room['current']['notes'] }}</td>
                <td>{{ $room['previous']['hotelName'] }}</td>
                <td>{{ $room['previous']['name'] }}</td>
                <td>{{ $room['previous']['description'] }}</td>
                <td>{{ $room['previous']['notes'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
