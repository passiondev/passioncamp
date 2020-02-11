@extends('layouts.bootstrap4')

@section('content')
    <div class="container">
        @include('errors.validation')

        <div class="card">
            <h4 class="card-header">Refund Transaction</h4>
            <div class="card-block">
                <form action="{{ action('TransactionRefundController@store', $transaction) }}" method="POST">
                    {{ csrf_field() }}

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-md-right">Transaction</label>
                        <div class="col-md-6">
                            <p class="form-control-static">{{ $transaction->transaction->identifier }}</p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-md-right">Original Amount</label>
                        <div class="col-md-6">
                            <p class="form-control-static">{{ \Money\Money::USD($transaction->transaction->amount / 100) }}</p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="amount" class="col-md-3 col-form-label text-md-right">Refund Amount</label>
                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="input-group-addon">$</div>
                                <input type="text" name="amount" id="amount" class="form-control">
                                <div class="input-group-addon">.00</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col offset-md-3">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
