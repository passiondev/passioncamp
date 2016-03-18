@extends('layouts.app')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1>Add Registration</h1>
        </header>

        {{ Form::open(['route' => 'order.store']) }}

            @if (Auth::user()->is_super_admin)
                <div class="form-group">
                    {{ Form::label('organization', 'Church', ['class' => 'control-label']) }}
                    {{ Form::select('organization', $organizationOptions, null, ['id' => 'organization', 'class' => 'form-control']) }}
                </div>
            @endif

            <div class="form-group">
                {{ Form::label('first_name', 'First Name', ['class' => 'control-label']) }}
                {{ Form::text('first_name', null, ['id' => 'first_name', 'class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('last_name', 'Last Name', ['class' => 'control-label']) }}
                {{ Form::text('last_name', null, ['id' => 'last_name', 'class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('phone', 'Phone Number', ['class' => 'control-label']) }}
                {{ Form::text('phone', null, ['id' => 'phone', 'class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('email', 'Email Address', ['class' => 'control-label']) }}
                {{ Form::email('email', null, ['id' => 'email', 'class' => 'form-control']) }}
            </div>
            <div class="form-group form-actions">
                <button class="btn btn-primary">Create Registration</button>
            </div>

        {{ Form::close() }}

    </div>
@stop
