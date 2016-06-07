@extends('layouts.semantic')

@section('content')
    <div class="ui container">
        <header class="page-header">
            <h1>Add Auth User</h1>
        </header>

        <div class="ui stackable grid">
            <div class="seven wide column">
                {{ Form::open(['route' => ['admin.organization.user.store', $organization], 'class' => 'ui form']) }}

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

                    <button class="ui primary button">Submit</button>

                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop
