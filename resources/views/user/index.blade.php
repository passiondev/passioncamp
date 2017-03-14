@extends('layouts.semantic')

@section('content')
    <div class="ui container">
        <header class="page-header">
            <h1>Users</h1>
            <a href="{{ action('UserController@create') }}">Add User</a>
        </header>

        <table class="ui very basic table">
            @foreach ($users as $user)
                <tr>
                    <td>{!! $user->email or '<i style="font-size:85%;font-weight:normal">none</i>' !!}</td>
                    <td>{{ $user->person->name or '' }}</td>
                    <td>{{ $user->auth_organization }}</td>
                    <td><a href="{{ action('UserController@edit', $user) }}">edit</a></td>
                    <td>
                        @can ('impersonate', $user)
                            <a href="{{ action('ImpersonationController@impersonate', $user) }}">impersonate</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@stop
