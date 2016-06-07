@extends('layouts.semantic')

@section('content')
    <div class="ui container">
        <header class="page-header">
            <div class="page-header__title">
                <h1>Add Attendee</h1>
                <h2>Registration #{{ $order->id }}</h2>
            </div>
        </header>

        {{ Form::open(['route' => ['order.ticket.store', $order], 'class' => 'ui form']) }}

            @include ('ticket/partials/form', ['submitButtonText' => 'Create Ticket'])
            
        {{ Form::close() }}
    </div>
@stop
