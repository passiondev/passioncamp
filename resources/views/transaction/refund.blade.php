@extends('layouts.app')

@section('content')
    <div class="container">
        {{ Form::open(['route' => ['transaction.refund.create', $transaction]]) }}
            <div class="form-group">
                <label>Transction</label>
                <p class="form-control-static">{{ $transaction->transaction->processor_transactionid }}</p>
            </div>
            <div class="form-group">
                {{ Form::label('amount', 'Amount', ['class' => 'control-label']) }}
                {{ Form::text('amount', null, ['id' => 'amount', 'class' => 'form-control']) }}
            </div>
            <div class="form-group form-actions">
                <button type="submit">Process Refund</button>
            </div>
        {{ Form::close() }}
    </div>
@stop
