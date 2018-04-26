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
                @foreach ($hotel->organizations->groupBy('id')->sortBy('church.name') as $organization)
                    <tr>
                        <td>
                            <a href="{{ route('admin.organizations.show', $organization->first()) }}">
                                {{ $organization->first()->church->name }}
                            </a>
                        </td>
                        <td>{{ $organization->sum('pivot.quantity') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@stop
