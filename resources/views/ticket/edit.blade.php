@extends('layouts.semantic')

@section('content')
    <div class="ui container">
        <header class="page-header">
            <div class="page-header__title">
                <h1>Edit Ticket</h1>
                <h2>Registration #{{ $ticket->order->id }}</h2>
            </div>
        </header>

        @include ('errors.validation')

        {{ Form::model($formData, ['route' => ['ticket.update', $ticket], 'method' => 'PATCH', 'class' => 'ui form']) }}

            @include ('ticket/partials/form', ['order' => $ticket->order, 'submitButtonText' => 'Update Ticket'])
            
        {{ Form::close() }}

        
        <footer style="display:flex;justify-content: flex-end">
            @unless ($ticket->is_canceled || Auth::user()->isOrderOwner())
                {{ Form::open(['route' => ['ticket.cancel', $ticket], 'method' => 'PATCH']) }}
                    <button class="ui yellow button" style="margin-right: 1rem">Cancel Ticket</button>
                {{ Form::close() }}
            @endif
            @if (Auth::user()->isSuperAdmin())
                {{ Form::open(['route' => ['ticket.delete', $ticket], 'method' => 'DELETE']) }}
                    <button class="ui red button">Delete Ticket</button>
                {{ Form::close() }}
            @endif
        </footer>

    </div>
@stop
