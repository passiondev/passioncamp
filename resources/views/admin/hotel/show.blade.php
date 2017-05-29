@extends('layouts.bootstrap4')

@section('content')
    <div class="container">
        <header>
            <h1>{{ $hotel->name }}</h1>
        </header>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Church Name</th>
                    <th>Rooms</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($hotel->organizations->sortBy('church.name')->groupBy('id') as $organization)
                    <tr>
                        <td>{{ $organization->first()->church->name }}</td>
                        <td>{{ $organization->sum('pivot.quantity') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@stop
