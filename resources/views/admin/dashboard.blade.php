@extends('layouts.bootstrap4')

@section('content')
    <div class="container">
        <div class="card-deck mb-3">
            <div class="card mb-3 text-center">
                <div class="card-block">
                    <h1>{{ number_format($data['num_churches']) }}</h1>
                </div>
                <div class="card-footer text-muted">Churches</div>
            </div>
            <div class="card mb-3 text-center">
                <div class="card-block">
                    <h1>{{ number_format($data['num_tickets']) }}</h1>
                </div>
                <div class="card-footer text-muted">Tickets</div>
            </div>
        </div>
        <div class="card-deck mb-3">
            <div class="card mb-3 text-center">
                <div class="card-block">
                    <h2>{{ money_format("%.0n", $data['total_cost']) }}</h2>
                </div>
                <div class="card-footer text-muted">Total Due</div>
            </div>
            <div class="card mb-3 text-center">
                <div class="card-block">
                    <h2>{{ money_format("%.0n", $data['balance']) }}</h2>
                </div>
                <div class="card-footer text-muted">Balance Remaining</div>
            </div>
        </div>
        <div class="card-deck mb-3">
            <div class="card mb-3 text-center">
                <div class="card-block">
                    <h2>{{ money_format("%.0n", $data['total_paid']) }}</h2>
                </div>
                <div class="card-footer text-muted">Total Paid</div>
            </div>
            <div class="card mb-3 text-center">
                <div class="card-block">
                    <h3>{{ money_format("%.0n", $data['stripe']) }}</h3>
                </div>
                <div class="card-footer text-muted">Stripe</div>
            </div>
            <div class="card mb-3 text-center">
                <div class="card-block">
                    <h3>{{ money_format("%.0n", $data['other']) }}</h3>
                </div>
                <div class="card-footer text-muted">Check / Other</div>
            </div>
        </div>
    </div>
@endsection
