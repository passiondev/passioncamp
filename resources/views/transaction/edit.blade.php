@extends('layouts.app')

@section('content')
    <div class="container">
        {{ Form::model($transactionData, ['route' => ['transaction.update', $transaction], 'method' => 'PATCH']) }}
            <div class="form-group">
                {{ Form::label('processor_transactionid', 'Transaction ID', ['class' => 'control-label']) }}
                {{ Form::text('processor_transactionid', null, ['id' => 'processor_transactionid', 'class' => 'form-control', 'placeholder' => 'Check or other indentifying #']) }}
            </div>
            <div class="form-group">
                {{ Form::label('amount', 'Amount', ['class' => 'control-label']) }}
                {{ Form::text('amount', null, ['id' => 'amount', 'class' => 'form-control', 'number']) }}
            </div>
            <div class="form-group form-actions">
                <button type="submit">Update Transaction</button>
            </div>
        {{ Form::close() }}
        <hr>

        <footer style="display:flex;justify-content:flex-end">
            {{ Form::open(['route' => ['transaction.delete', $transaction], 'method' => 'DELETE']) }}
                <button class="outline danger small">Delete Transaction</button>
            {{ Form::close() }}
        </footer>
    </div>
@stop
