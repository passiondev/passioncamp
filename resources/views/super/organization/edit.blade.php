@extends('layouts.bootstrap4')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1>Update Church</h1>
        </header>

        <form action="{{ action('OrganizationController@update', $organization) }}" method="POST">
            {{ method_field('PUT') }}
            {{ csrf_field() }}

            @include('organization.partials.form')

            <hr>

            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input" name="checked_in" @if ($organization->setting('checked_in')) checked @endif>
                    Checked In
                </label>
            </div>

            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input" name="group_leader_agreement_signed" @if ($organization->setting('group_leader_agreement_signed')) checked @endif>
                    Group Leader Agreement Signed
                </label>
            </div>

            <hr>

            <button class="btn btn-primary">Update Church</button>
        </form>
    </div>
@stop
