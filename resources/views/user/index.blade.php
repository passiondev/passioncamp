@extends('layouts.bootstrap4')

@section('content')
    <div class="container-fluid">
        <header class="d-flex justify-content-between align-items-bottom">
            <h1>Users</h1>
            <p>
                <a class="btn btn-secondary" href="{{ action('Super\UserController@create') }}">Add Admin</a>
            </p>
        </header>


        <table class="table table-responsive table-striped align-middle">
            @foreach ($users as $user)
                <tr>
                    <td>{!! $user->email ?? '<i style="font-size:85%;font-weight:normal">none</i>' !!}</td>
                    <td>{{ $user->person->name ?? '' }}</td>
                    <td style="line-height: 1">
                        @if (! $user->isRegistered())
                            <input type="text" style="margin-bottom:0" readonly value="{{ url()->signedRoute('auth.register.create', $user) }}">
                        @elseif ($user->access == 100)
                            PASSION CAMP ADMIN
                        @else
                            {{ $user->organization->church->name }}<br>
                            <small><em>{{ $user->organization->church->location }}</em></small>
                        @endif
                    </td>
                    <td><a href="{{ action('Super\UserController@edit', $user) }}" class="btn btn-sm btn-outline-secondary">edit</a></td>
                    <td>
                        @can ('impersonate', $user)
                            <a href="{{ action('Auth\ImpersonationController@impersonate', $user) }}" class="btn btn-sm btn-outline-secondary">impersonate</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
        {{ $users->links() }}
    </div>
@stop
