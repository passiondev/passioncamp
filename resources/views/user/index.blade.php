@extends('layouts.app')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1>Users</h1>
        </header>

        <table class="table">
            @foreach ($users as $user)
                <tr>
                    <th>{!! $user->username or '<i style="font-size:85%;font-weight:normal">none</i>' !!}</th>
                    <td>{{ $user->person->name or '' }}</td>
                    <td>{{ $user->auth_organization }}</td>
                </tr>
            @endforeach
        </table>
    </div>
@stop
