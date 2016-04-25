@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-header">
            <h1>Account Settings</h1>
        </div>

        <section class="row">
            <div class="large-4 columns">
                <h4>Church</h4>
                <dl class="m-b-2">
                    <dt>{{ $organization->church->name }}</dt>
                    @if ($organization->church->street)
                        <dd>{{ $organization->church->street }}<br>{{ $organization->church->city }}, {{ $organization->church->state }} {{ $organization->church->zip }}</dd>
                    @endif
                    <dd>{{ $organization->church->website }}</dd>
                    <dd>{{ $organization->church->pastor_name }}</dd>
                </dl>
            </div>
            @if ($organization->contact)
                <div class="large-4 columns">
                    <h4>Contact</h4>
                    <dl class="m-b-2">
                        <dt>{{ $organization->contact->name }}</dt>
                        <dd>{{ $organization->contact->email }}</dd>
                        <dd>{{ $organization->contact->phone }}</dd>
                        <dd>{{ $organization->contact_desciption }}</dd>
                    </dl>
                </div>
            @endif
            @if ($organization->studentPastor)
                <div class="large-4 columns">
                    <h4>Student Pastor</h4>
                    <dl class="m-b-2">
                        <dt>{{ $organization->studentPastor->name }}</dt>
                        <dd>{{ $organization->studentPastor->email }}</dd>
                        <dd>{{ $organization->studentPastor->phone }}</dd>
                    </dl>
                </div>
            @endif

        </section>
        
        <section>
            <header class="section__header">
                <h3>Auth Users</h3>
                <a href="{{ route('user.create') }}" class="button small">Add User</a>
            </header>
            <table>
                <tr>
                    @foreach (auth()->user()->organization->authUsers as $user)
                        <tr>
                            <td>{{ $user->person->name ?? '' }}</td>
                            <td>{{ $user->email }}</td>
                            <td><a href="{{ route('user.edit', $user) }}">edit</a></td>
                            <td>
                                @unless ($user->password)
                                    <small><i>pending</i></small>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tr>
            </table>
        </section>
    </div>
@stop
