@extends('layouts.semantic')

@section('content')
    <div class="ui container">
        {{ Form::open(['route' => ['transaction.refund.create', $transaction], 'class' => 'ui form']) }}
            <div class="field">
                <label>Transction</label>
                <p class="form-control-static">{{ $transaction->transaction->processor_transactionid }}</p>
            </div>

            <div class="field">
                {{ Form::label('amount', 'Amount') }}
                <div class="ui right labeled input">
                    <div class="ui label">$</div>
                    {{ Form::text('amount', null, ['id' => 'amount']) }}
                    <div class="ui basic label">.00</div>
                </div>
            </div>

            <button class="ui primary button" type="submit">Process Refund</button>
        {{ Form::close() }}
    </div>
@stop
