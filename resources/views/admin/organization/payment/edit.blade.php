@extends('layouts.bootstrap4')


@section('content')
    <div class="container">
        <header>
            <h1>{{ $organization->church->name }}</h1>
        </header>

        @include('errors.validation')

        <div class="card mb-3">
            <h4 class="card-header">Edit Payment</h4>
            <div class="card-block">
                <form action="{{ action('OrganizationPaymentController@update', [$organization, $payment]) }}" method="POST">
                    @method('PUT')
                    @csrf

                    <div class="form-group row">
                        <label for="type" class="col-md-3 col-form-label text-md-right">Type</label>
                        <div class="col-md-8 col-lg-6">
                            <select name="source" id="type" class="form-control">
                                @foreach ($sources as $key => $name)
                                    <option
                                        value="{{ $key }}"
                                        @if ($key == old('source', $payment->transaction->source))
                                            selected="selected"
                                        @endif
                                    >
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="identifier" class="col-md-3 col-form-label text-md-right">Identifier</label>
                        <div class="col-md-8 col-lg-6">
                            <input type="text" name="identifier" id="identifier" class="form-control" value="{{ old('identifier', $payment->transaction->identifier) }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="amount" class="col-md-3 col-form-label text-md-right">Amount</label>
                        <div class="col-md-8 col-lg-6">
                            <div class="input-group">
                                <div class="input-group-addon">$</div>
                                <input type="number" name="amount" id="amount" value="{{ old('amount', $payment->amount / 100) }}" class="form-control">
                                <div class="input-group-addon">.00</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8 col-lg-6 offset-md-3">
                            <button type="submit" class="btn btn-primary">Update Payment</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="text-right">
            <button
                class="btn btn-sm btn-danger"
                onclick="
                    return confirm('Delete this payment?')
                    ? document.getElementById('delete-payment-{{ $payment->id }}-form').submit()
                    : false
                "
            >
                Delete Payment
            </button>
        </div>
    </div>
    <form id="delete-payment-{{ $payment->id }}-form" action="{{ action('OrganizationPaymentController@destroy', [$organization, $payment]) }}" method="POST">
        @method('DELETE')
        @csrf
    </form>
@stop
