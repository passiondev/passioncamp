@extends('layouts.app')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1>Edit Contact</h1>
        </header>

        <div class="row">
            <div class="large-5 columns">
                {{ Form::model($contact, ['route' => ['order.contact.update', $order], 'method' => 'PATCH']) }}

                    <div class="form-group">
                        {{ Form::label('first_name', 'First Name', ['class' => 'control-label']) }}
                        {{ Form::text('first_name', null, ['id' => 'first_name', 'class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('last_name', 'Last Name', ['class' => 'control-label']) }}
                        {{ Form::text('last_name', null, ['id' => 'last_name', 'class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('email', 'Email Address', ['class' => 'control-label']) }}
                        {{ Form::email('email', null, ['id' => 'email', 'class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('phone', 'Phone Number', ['class' => 'control-label']) }}
                        {{ Form::text('phone', null, ['id' => 'phone', 'class' => 'form-control']) }}
                    </div>
                    <div class="form-group form-actions">
                        <button type="submit">Update</button>
                        <a href="{{ route('order.show', $order) }}" style="margin-left:1rem">Go Back</a>
                    </div>

                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop

