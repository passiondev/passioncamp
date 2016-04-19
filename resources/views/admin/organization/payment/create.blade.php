@extends('layouts.app')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1>Add Payment</h1>
        </header>

        <div class="row">
            <div class="medium-5 columns">
                {{ Form::open(['route' => (auth()->user()->is_super_admin ? ['admin.organization.payment.store', $organization] : ['payment.store']), 'id' => 'transactionForm', 'can-make-stripe-payments' => '1', 'novalidate', 'v-on:submit.prevent' => 'submitHandler']) }}

                    <p class="payment-errors text-danger"></p>

                    @if (auth()->user()->is_super_admin)
                        <div class="form-group">
                            {{ Form::label('type', 'Payment Method', ['class' => 'control-label']) }}
                            {{ Form::select('type', ['credit' => 'Credit', 'check' => 'Check'], null, ['id' => 'type', 'class' => 'form-control', 'v-model' => 'payment_method']) }}
                        </div>
                    @else
                        <input type="hidden" name="type" value="credit">
                    @endif
                    <div class="payment_method payment_method--other" v-show="payment_method != 'credit'">
                        <div class="form-group">
                            {{ Form::label('transaction_id', 'Transaction ID', ['class' => 'control-label']) }}
                            {{ Form::text('transaction_id', null, ['id' => 'transaction_id', 'class' => 'form-control', 'placeholder' => 'Check or other indentifying #']) }}
                        </div>
                    </div>
                    <div class="payment_method payment_method--credit" v-show="payment_method == 'credit'">
                        <div class="form-group">
                            <label for="cc_number" class="control-label">Card Number</label>
                            <input type="text" id="cc_number" class="form-control js-form-input-card-number" data-stripe="number" required>
                        </div>

                        <div class="row">
                            <div class="form-group small-6 columns">
                                <label for="cc_exp_month" class="control-label">Expiration</label>
                                <input type="text" id="cc_expiry" class="form-control js-form-input-card-expiry" placeholder="mm / yy" data-stripe="exp" required>
                            </div>
                            <div class="form-group small-6 columns">
                                <label for="cc_cvc" class="control-label">CVC</label>
                                <input id="cc_cvc" type="text" size="8" data-stripe="cvc" class="form-control js-form-input-card-cvc">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('amount', 'Amount', ['class' => 'control-label']) }}
                        <div class="input-group">
                            <span class="input-group-label">$</span>
                            {{ Form::text('amount', $organization->balance < 0 ? '' : $organization->balance, ['id' => 'amount', 'class' => 'input-group-field form-control', 'number']) }}
                            <span class="input-group-label">.00</span>
                        </div>
                    </div>
                    <div class="form-group form-actions">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('account.dashboard') }}" style="margin-left:1rem">Cancel</a>
                    </div>

                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop

@section('foot')
    <script src="https://js.stripe.com/v2/"></script>
    <script>
        Stripe.setPublishableKey('{{ config('services.stripe.key') }}');
    </script>
@endsection