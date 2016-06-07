@extends('layouts.semantic')

@section('content')
    <div class="ui container">
        <div class="ui dividing header page-header">
            <h1>Account Settings</h1>
        </div>

        <section class="ui three column grid">
            <div class="column">
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
                <div class="column">
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
                <div class="column">
                    <h4>Student Pastor</h4>
                    <dl class="m-b-2">
                        <dt>{{ $organization->studentPastor->name }}</dt>
                        <dd>{{ $organization->studentPastor->email }}</dd>
                        <dd>{{ $organization->studentPastor->phone }}</dd>
                    </dl>
                </div>
            @endif
        </section>
        
        <div class="ui divider"></div>

        <section class="ui segment">
            <header class="ui header section__header">
                <h3>Auth Users</h3>
                <div class="sub header">
                    <a href="{{ route('user.create') }}" class="button small">Add User</a>
                </div>
            </header>
            <table class="ui very basic table">
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
