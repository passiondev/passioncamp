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

            </tbody>
        </table>
    </div>
@stop
