@extends('layouts.semantic')

@section('content')
    <div class="ui container">
        <header class="ui dividing header">
            <h1 class="page-header__title">{{ $organization->church->name }}</h1>
            <div class="sub header page-header__actions">
                <a class="button small" href="{{ route('admin.organization.item.create', $organization) }}">Add Item</a>                
                <a class="button small" href="{{ route('admin.organization.edit', $organization) }}">Edit Church</a>
            </div>
        </header>

        <section class="ui stackable grid">
            <div class="six wide column">
                <h4>Church</h4>
                <dl class="m-b-2">
                    <dt>{{ $organization->church->name }}</dt>
                    <dd>{{ $organization->church->street }}<br>{{ $organization->church->city }}, {{ $organization->church->state }} {{ $organization->church->zip }}</dd>
                    <dd>{{ $organization->church->website }}</dd>
                    <dd>{{ $organization->church->pastor_name }}</dd>
                </dl>
                <h4>Contact</h4>
                <dl class="m-b-2">
                    <dt>{{ $organization->contact->name }}</dt>
                    <dd>{{ $organization->contact->email }}</dd>
                    <dd>{{ $organization->contact->phone }}</dd>
                    <dd>{{ $organization->contact_desciption }}</dd>
                </dl>
                <h4>Student Pastor</h4>
                <dl class="m-b-2">
                    <dt>{{ $organization->studentPastor->name }}</dt>
                    <dd>{{ $organization->studentPastor->email }}</dd>
                    <dd>{{ $organization->studentPastor->phone }}</dd>
                </dl>
            </div>
            <div class="ten wide column">
                @include('organization/partials/billing_summary')
            </div>
        </section>

        <section class="ui segment">
            <header class="ui dividing header">
                <h3>Auth Users</h3>
                <div class="sub header"><a href="{{ route('admin.organization.user.create', $organization) }}">Add Auth User</a></div>
            </header>
            @if ($organization->authUsers->count() > 0)
                <table class="ui very basic striped fixed table">
                    @foreach ($organization->authUsers as $user)
                        <tr>
                            <td>{{ $user->person->name or '' }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <div class="ui fluid input">
                                    <input type="text" style="margin-bottom:0" readonly value="{{ route('complete.registration', [$user, $user->hash]) }}">
                                </div>
                            </td>
                            <td>
                                @can ('impersonate', $user)
                                    <a href="{{ route('user.impersonate', $user) }}">impersonate</a>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </table>
            @endif
        </section>
        <section class="ui segment" id="notes">
            <header class="ui dividing header panel-heading">
                <h3>Notes</h3>
            </header>
            <div class="ui comments">
                @foreach ($organization->notes as $note)
                    <div class="comment">
                        <div class="content">
                            <span class="author">{{ $note->author ? $note->author->email :'' }}</span>
                            <div class="metadata">
                                <span class="date">{{ $note->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="text">
                                {!! nl2br($note->body) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {!! Form::open(['route' => ['admin.organization.note.store', $organization], 'class' => 'ui form']) !!}
                <div class="field">
                    {!! Form::textarea('body', null, ['rows' => '3']) !!}
                </div>
                <button class="ui primary button">Add Note</button>
            {!! Form::close() !!}
        </section>
    </div>
@stop
