@extends('layouts.semantic')

@section('content')
    <div class="ui container">
        <header class="page-header">
            <h1>Add Registration</h1>
        </header>

        <div class="ui stackable grid">
            <div class="seven wide column">
                {{ Form::open(['route' => 'order.store', 'class' => 'ui form']) }}

                    @if (Auth::user()->is_super_admin)
                        <div class="field">
                            {{ Form::label('organization', 'Church') }}
                            {{ Form::select('organization', $organizationOptions, null, ['id' => 'organization', 'class' => 'ui dropdown']) }}
                        </div>
                    @endif

                    <h4>Parent/Contact Information</h4>
                    <div class="field">
                        {{ Form::label('first_name', 'First Name') }}
                        {{ Form::text('first_name', null, ['id' => 'first_name']) }}
                    </div>
                    <div class="field">
                        {{ Form::label('last_name', 'Last Name') }}
                        {{ Form::text('last_name', null, ['id' => 'last_name']) }}
                    </div>
                    <div class="field">
                        {{ Form::label('email', 'Email Address') }}
                        {{ Form::email('email', null, ['id' => 'email']) }}
                    </div>
                    <div class="field">
                        {{ Form::label('phone', 'Phone Number') }}
                        {{ Form::text('phone', null, ['id' => 'phone']) }}
                    </div>
                    <button class="ui primary button">Create Registration</button>

                {{ Form::close() }}
            </div>
        </div>

    </div>
@stop
