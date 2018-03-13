@extends('layouts.bootstrap4')

@section('content')
    <div class="container">
        <h1>Account Settings</h1>

        <div class="card-deck">
            <div class="card mb-3">
                <h4 class="card-header">Church</h4>
                <div class="card-block">
                    <dl>
                        <dt>{{ $organization->church->name }}</dt>
                        @if ($organization->church->street)
                            <dd>{{ $organization->church->street }}<br>{{ $organization->church->city }}, {{ $organization->church->state }} {{ $organization->church->zip }}</dd>
                        @endif
                        <dd>{{ $organization->church->website }}</dd>
                        <dd>{{ $organization->church->pastor_name }}</dd>
                    </dl>
                </div>
            </div>
            @if ($organization->contact)
                <div class="card mb-3">
                    <h4 class="card-header">Contact</h4>
                    <div class="card-block">
                        <dl class="m-b-2">
                            <dt>{{ $organization->contact->name }}</dt>
                            <dd>{{ $organization->contact->email }}</dd>
                            <dd>{{ $organization->contact->phone }}</dd>
                            <dd>{{ $organization->contact_desciption }}</dd>
                        </dl>
                    </div>
                </div>
            @endif
            @if ($organization->studentPastor)
                <div class="card mb-3">
                    <h4 class="card-header">Student Pastor</h4>
                    <div class="card-block">
                        <dl class="m-b-2">
                            <dt>{{ $organization->studentPastor->name }}</dt>
                            <dd>{{ $organization->studentPastor->email }}</dd>
                            <dd>{{ $organization->studentPastor->phone }}</dd>
                        </dl>
                    </div>
                </div>
            @endif
        </div>

        <div class="card">
            <header class="card-header d-flex justify-content-between align-items-center">
                <h3>Admin Users</h3>
                <div class="sub header">
                    <a href="{{ action('Account\UserController@create') }}" class="btn btn-secondary btn-sm">Add User</a>
                </div>
            </header>
            <div class="card-block">
                <table class="table table-responsive">
                    <tr>
                        @foreach (auth()->user()->organization->authUsers as $user)
                            <tr>
                                <td>{{ $user->person->name ?? '' }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @unless ($user->password)
                                        <small><i>pending</i></small>
                                    @endif

                                    @can ('destroy', new App\AccountUser($user->organization, $user))
                                        <a
                                            title="Remove user"
                                            class="btn btn-sm btn-outline-secondary ml-2"
                                            href="{{ route('account.users.destroy', $user) }}"
                                            onclick="event.preventDefault(); if (confirm('Remove this user?')) document.getElementById('account-users-destroy-form-{{ $user->id }}').submit(); else return;"
                                        >
                                            &times;
                                        </a>

                                        <form action="{{ route('account.users.destroy', $user) }}" method="POST" id="account-users-destroy-form-{{ $user->id }}">
                                            @method('DELETE')
                                            @csrf
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tr>
                </table>
            </div>
        </div>
    </div>
@stop
