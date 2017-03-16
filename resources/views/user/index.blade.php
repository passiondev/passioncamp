@extends('layouts.bootstrap4')

@section('content')
    <div class="container">
        <header class="d-flex justify-content-between align-items-bottom">
            <h1>Users</h1>
            <p>
                <a class="btn btn-secondary" href="{{ action('UserController@create') }}">Add User</a>
            </p>
        </header>


        <table class="table table-striped align-middle">
            @foreach ($users as $user)
                <tr>
                    <td>{!! $user->email or '<i style="font-size:85%;font-weight:normal">none</i>' !!}</td>
                    <td>{{ $user->person->name or '' }}</td>
                    <td style="line-height: 1">
                        @if ($user->access == 100)
                            PASSION CAMP ADMIN
                        @else
                            {{ $user->organization->church->name }}<br>
                            <small><em>{{ $user->organization->church->location }}</em></small>
                        @endif
                    </td>
                    <td><a href="{{ action('UserController@edit', $user) }}">edit</a></td>
                    <td>
                        @can ('impersonate', $user)
                            <a href="{{ action('ImpersonationController@impersonate', $user) }}">impersonate</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
        {{ $users->links() }}
    </div>
@stop
