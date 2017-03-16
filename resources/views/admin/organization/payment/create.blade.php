@extends('layouts.semantic')

@section('content')
    <div class="container">
        <header>
            <h1>Add Payment</h1>
        </header>

        <div class="ui stackable grid">
            <div class="seven wide column">
                <Transaction inline-template can-make-stripe-payments="1">
                    {{-- {{ Form::open(['route' => (auth()->user()->isSuperAdmin() ? ['admin.organization.payment.store', $organization] : ['payment.store']), 'id' => 'transactionForm', 'novalidate', 'v-on:submit.prevent' => 'submitHandler', 'class' => 'ui form']) }} --}}

                        <div class="ui visible error message payment-errors" v-show="errors.length > 0">
                            <div class="header">
                                There was an error with your submission.
                            </div>
                            <ul class="list">
                                <li v-for="error in errors">@{{ error }}</li>
                            </ul>
                        </div>

                        @if (auth()->user()->isSuperAdmin())
                            <div class="field">
                                {{ Form::label('type', 'Payment Method') }}
                                {{ Form::select('type', ['credit' => 'Credit', 'check' => 'Check'], null, ['id' => 'type', 'v-model' => 'payment_method', 'class' => 'ui dropdown']) }}
                            </div>
                        @else
                            <input type="hidden" name="type" value="credit">
                        @endif
                        <div class="field" v-show="payment_method != 'credit'">
                            {{ Form::label('transaction_id', 'Transaction ID') }}
                            {{ Form::text('transaction_id', null, ['id' => 'transaction_id', 'placeholder' => 'Check or other indentifying #']) }}
                        </div>

                        <div class="field" v-show="payment_method == 'credit'">
                            <label for="cc_number" class="control-label">Card Number</label>
                            <input type="text" id="cc_number" class="form-control js-form-input-card-number" data-stripe="number" required>
                        </div>

                        <div class="two fields" v-show="payment_method == 'credit'">
                            <div class="field">
                                <label for="cc_exp_month" class="control-label">Expiration</label>
                                <input type="text" id="cc_expiry" class="form-control js-form-input-card-expiry" placeholder="mm / yy" data-stripe="exp" required>
                            </div>
                            <div class="field">
                                <label for="cc_cvc" class="control-label">CVC</label>
                                <input id="cc_cvc" type="text" size="8" data-stripe="cvc" class="form-control js-form-input-card-cvc">
                            </div>
                        </div>

                        <div class="field">
                            {{ Form::label('amount', 'Amount') }}
                            <div class="ui right labeled input">
                                <div class="ui label">$</div>
                                {{ Form::text('amount', $organization->balance < 0 ? '' : $organization->balance, ['id' => 'amount', 'class' => 'input-group-field form-control', 'number']) }}
                                <div class="ui basic label">.00</div>
                            </div>
                        </div>

                        <div class="field form-actions">
                            <button type="submit" class="ui primary button">Submit</button>
                            {{-- <a href="{{ route('account.dashboard') }}" style="margin-left:1rem">Cancel</a> --}}
                        </div>

                    {{ Form::close() }}
                </Transaction>
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
