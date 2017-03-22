@extends('layouts.bootstrap4')

@section('content')
    <div class="container">
        <div class="card mb-3">
            <h4 class="card-header">Edit Tranasction</h4>
            <div class="card-block">
                <form action="{{ action('TransactionController@update', $transaction) }}" method="POST">
                    {{ method_field('PATCH') }}
                    {{ csrf_field() }}

                    <div class="form-group row">
                        <label for="identifier" class="col-md-3 col-form-label">Identifier</label>
                        <div class="col-md-6">
                            <input type="text" name="identifier" id="identifier" class="form-control" value="{{ old('identifier', $transaction->transaction->identifier) }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="amount" class="col-md-3 col-form-label">Amount</label>
                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="input-group-addon">$</div>
                                <input type="number" name="amount" id="amount" class="form-control" value="{{ old('amount', $transaction->transaction->amount / 100) }}">
                                <div class="input-group-addon">.00</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col offset-md-3">
                            <button type="submit" class="btn btn-primary">Update Transaction</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <footer class="text-right">
            <a href="{{ action('TransactionController@delete', $transaction) }}" class="btn btn-outline-danger btn-sm" onclick="event.preventDefault(); document.getElementById('delete-form').submit();">Delete</a>
        </footer>

        <form action="{{ action('TransactionController@delete', $transaction) }}" method="POST" id="delete-form">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
        </form>
    </div>
@stop
