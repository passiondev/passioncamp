@extends('layouts.app')

@section('content')
    <div class="container">
        <header class="page-header">
            <div class="page-header__title">
                <h1>Edit Ticket</h1>
                <h2>Registration #{{ $ticket->order->id }}</h2>
            </div>
        </header>

        {{ Form::model($formData, ['route' => ['ticket.update', $ticket], 'method' => 'PATCH']) }}

            @include ('ticket/partials/form', ['order' => $ticket->order, 'submitButtonText' => 'Update Ticket'])
            
        {{ Form::close() }}

        <hr>
        
        <footer style="display:flex;justify-content: flex-end">
            @unless ($ticket->is_canceled)
                {{ Form::open(['route' => ['ticket.cancel', $ticket], 'method' => 'PATCH']) }}
                    <button class="outline warning small" style="margin-right: 1rem">Cancel Ticket</button>
                {{ Form::close() }}
            @endif
            @if (Auth::user()->is_super_admin)
                {{ Form::open(['route' => ['ticket.delete', $ticket], 'method' => 'DELETE']) }}
                    <button class="outline danger small">Delete Ticket</button>
                {{ Form::close() }}
            @endif
        </footer>

    </div>
@stop
