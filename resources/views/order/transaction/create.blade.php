@extends('layouts.semantic')

@section('head')
<script>
    window.store.Transaction = {!! json_encode([
        'errors' => [],
        'payment_method' => old('type', collect($payment_methods)->keys()->first()),
    ]) !!};
</script>
@endsection

@section('content')
    <div class="ui container">
        <header class="page-header">
            <h1>Add Transaction</h1>
            <h2>Registration #{{ $order->id }}</h2>
        </header>

        <div class="ui stackable grid">
            <div class="seven wide column">
                <Transaction inline-template can-make-stripe-payments="{{ $order->organization->can_make_stripe_payments }}">
                    {{ Form::open(['route' => ['order.transaction.store', $order], 'id' => 'transactionForm', 'novalidate', 'v-on:submit.prevent' => 'submitHandler', 'class' => 'ui form']) }}

                        <div class="ui visible error message payment-errors" v-if="Payment.errors.length">
                            <div class="header">
                                There was an error with your submission.
                            </div>
                            <ul class="list">
                                <li v-for="error in Payment.errors">@{{ error }}</li>
                            </ul>
                        </div>

                        <div class="field">
                            {{ Form::label('type', 'Payment Method') }}
                            {{ Form::select('type', $payment_methods, null, ['id' => 'type', 'class' => 'ui dropdown', 'v-model' => 'payment_method']) }}
                        </div>
                        <div class="field" v-show="payment_method != 'credit'">
                            {{ Form::label('transaction_id', 'Transaction ID') }}
                            {{ Form::text('transaction_id', null, ['id' => 'transaction_id', 'placeholder' => 'Check or other indentifying #']) }}
                        </div>
                        @if ($order->organization->can_make_stripe_payments)
                            <div class="field" v-show="payment_method == 'credit'">
                                <label for="cc_number" class="control-label">Card Number</label>
                                <input type="text" id="cc_number" class="js-form-input-card-number" required v-model="Payment.card_number">
                            </div>

                            <div class="two fields" v-show="payment_method == 'credit'">
                                <div class="field">
                                    <label for="cc_exp_month" class="control-label">Expiration</label>
                                    <input type="text" id="cc_expiry" class="js-form-input-card-expiry" placeholder="mm / yy" required v-model="Payment.card_exp">
                                </div>
                                <div class="field">
                                    <label for="cc_cvc" class="control-label">CVC</label>
                                    <input id="cc_cvc" type="text" size="8" class="js-form-input-card-cvc" v-model="Payment.card_cvc">
                                </div>
                            </div>
                        @endif
                        <div class="field">
                            {{ Form::label('amount', 'Amount') }}
                            <div class="ui right labeled input">
                                <div class="ui label">$</div>
                                {{ Form::text('amount', $order->balance < 0 ? '' : $order->balance, ['id' => 'amount', 'number']) }}
                                <div class="ui basic label">.00</div>
                            </div>
                        </div>

                        <button :disable="Payment.occupied" type="submit" class="ui primary button">Submit</button>

                    {{ Form::close() }}
                </Transaction>
            </div>
        </div>
    </div>
@stop

@section('foot')
    <script src="https://js.stripe.com/v2/"></script>
    <script>
        Stripe.setPublishableKey('{{ config('settings.stripe.key') }}');
    </script>
@endsection
