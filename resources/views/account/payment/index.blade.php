@extends('layouts.bootstrap4')


@section('content')
    <div class="container">
        @include('errors.validation')

        <div class="card mb-3">
            <h4 class="card-header">Make A Payment</h4>
            <div class="card-block">
                <account-payment-form inline-template stripe-elements="card-element">
                    <form action="{{ action('Account\PaymentController@store') }}" method="POST" v-on:submit.prevent="elementsSubmitHandler">
                        {{ csrf_field() }}

                        <div class="form-group row">
                            <label for="amount" class="col-md-3 col-form-label text-md-right">Amount</label>
                            <div class="col-md-8 col-lg-6">
                                <div class="input-group">
                                    <div class="input-group-addon">$</div>
                                    <input type="number" name="amount" id="amount" value="{{ old('amount', $balance / 100) }}" class="form-control">
                                    <div class="input-group-addon">.00</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">Credit Card</label>
                            <div class="col-md-8 col-lg-6">
                                <div id="card-element" class="form-control"></div>
                                <div v-if="Payment.errors.length" class="alert alert-danger mt-2 mb-0">
                                    <ul>
                                        <li v-for="error in Payment.errors">@{{ error }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col offset-md-3">
                                <button type="submit" class="btn btn-primary">Submit Payment</button>
                            </div>
                        </div>
                    </form>
                </account-payment-form>
            </div>
        </div>

        <div class="card">
            <h4 class="card-header">All Transactions</h4>
            <div>
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    @foreach ($transactions->reverse() as $split)
                        <tr>
                            <td>{{ $split->name }}</td>
                            <td>{{ money_format('%.2n', $split->amount / 100) }}</td>
                            <td><time datetime="{{ $split->created_at->toAtomString() }}" title="{{ $split->created_at->toAtomString() }}">{{ $split->created_at->diffForHumans() }}</time></td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>

@endsection

@section('foot')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        var stripe = Stripe('pk_test_NhPP1ufSIsnkRBJVEvrGZ8Zl');
    </script>
@endsection
