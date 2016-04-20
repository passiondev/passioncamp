@extends('layouts.app')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1 class="page-header__title">{{ $organization->church->name }}</h1>
            <div class="page-header__actions">
                <a class="button small" href="{{ route('admin.organization.item.create', $organization) }}">Add Item</a>                
                <a class="button small" href="{{ route('admin.organization.edit', $organization) }}">Edit Church</a>
            </div>
        </header>

        <section class="row">
            <div class="large-4 columns">
                <h5>Church</h5>
                <dl class="m-b-2">
                    <dt>{{ $organization->church->name }}</dt>
                    <dd>{{ $organization->church->street }}<br>{{ $organization->church->city }}, {{ $organization->church->state }} {{ $organization->church->zip }}</dd>
                    <dd>{{ $organization->church->website }}</dd>
                    <dd>{{ $organization->church->pastor_name }}</dd>
                </dl>
                <h5>Contact</h5>
                <dl class="m-b-2">
                    <dt>{{ $organization->contact->name }}</dt>
                    <dd>{{ $organization->contact->email }}</dd>
                    <dd>{{ $organization->contact->phone }}</dd>
                    <dd>{{ $organization->contact_desciption }}</dd>
                </dl>
                <h5>Student Pastor</h5>
                <dl class="m-b-2">
                    <dt>{{ $organization->studentPastor->name }}</dt>
                    <dd>{{ $organization->studentPastor->email }}</dd>
                    <dd>{{ $organization->studentPastor->phone }}</dd>
                </dl>
            </div>
            <div class="large-8 columns">
                @include('organization/partials/billing_summary')
            </div>
        </section>

        <section>
            <header class="section__header">
                <h3>Auth Users</h3>
                <a href="{{ route('admin.organization.user.create', $organization) }}">Add Auth User</a>
            </header>
            @if ($organization->authUsers->count() > 0)
                <table class="table table-striped">
                    @foreach ($organization->authUsers as $user)
                        <tr>
                            <th>{{ $user->person->name or '' }}</th>
                            <td>{{ $user->email }}</td>
                            <td><input type="text" style="margin-bottom:0" readonly value="{{ route('complete.registration', [$user, $user->hash]) }}"></td>
                        </tr>
                    @endforeach
                </table>
            @endif
        </section>
    </div>
@stop
