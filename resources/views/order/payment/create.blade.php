@extends('layouts.semantic')

@section('content')
    <div class="ui container">
        <header class="page-header">
            <h1>Add Payment</h1>
            <h2>Registration #{{ $order->id }}</h2>
        </header>

        <div class="ui stackable grid">
            <div class="seven wide column">
                <Transaction inline-template can-make-stripe-payments="{{ $order->organization->can_make_stripe_payments }}">
                    {{ Form::open(['route' => ['order.payment.store', $order], 'id' => 'transactionForm', 'novalidate', 'v-on:submit.prevent' => 'submitHandler', 'class' => 'ui form']) }}

                        @if (session('error'))
                            <div class="ui visible error message">
                                <p>{{ session('error') }}</p>
                            </div>
                        @endif
                        @if ($errors->count())
                            <div class="ui visible error message">
                                <div class="header">
                                    There was an error with your submission.
                                </div>
                                <ul class="list">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="ui visible error message" v-show="errors.length > 0">
                            <div class="header">
                                There was an error with your submission.
                            </div>
                            <ul class="list">
                                <li v-for="error in errors">@{{ error }}</li>
                            </ul>
                        </div>

                        <div class="field">
                            <label for="cc_number" class="control-label">Card Number</label>
                            <input type="text" id="cc_number" class="js-form-input-card-number" data-stripe="number" required>
                        </div>

                        <div class="two fields" v-show="payment_method == 'credit'">
                            <div class="field">
                                <label for="cc_exp_month" class="control-label">Expiration</label>
                                <input type="text" id="cc_expiry" class="js-form-input-card-expiry" placeholder="mm / yy" data-stripe="exp" required>
                            </div>
                            <div class="field">
                                <label for="cc_cvc" class="control-label">CVC</label>
                                <input id="cc_cvc" type="text" size="8" data-stripe="cvc" class="js-form-input-card-cvc">
                            </div>
                        </div>

                        <div class="field">
                            <label for="amount">Amount</label>
                            <div class="ui right labeled input">
                                <div class="ui label">$</div>
                                {{ Form::text('amount', $order->balance < 0 ? '' : $order->balance, ['id' => 'amount', 'number']) }}
                                <div class="ui basic label">.00</div>
                            </div>
                        </div>

                        <button type="submit" class="ui primary button">Submit</button>

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