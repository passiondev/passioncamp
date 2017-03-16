@extends('layouts.bootstrap4')

@section('content')
    <div class="container">
        <div class="card-deck mb-3">
            <div class="card text-center">
                <div class="card-block">
                    <h1>{{ App\Organization::count() }}</h1>
                </div>
                <div class="card-footer text-muted">Churches</div>
            </div>
            <div class="card text-center">
                <div class="card-block">
                    <h1>{{ App\Organization::with('tickets')->get()->sumTicketQuantity() }}</h1>
                </div>
                <div class="card-footer text-muted">Tickets</div>
            </div>
        </div>

        <div class="card-deck mb-3">
            <div class="card text-center">
                <div class="card-block">
                    <h2>{{ money_format("%.2n", App\Organization::totalPaid()) }}</h2>
                </div>
                <div class="card-footer text-muted">Total Paid</div>
            </div>
            <div class="card text-center">
                <div class="card-block">
                    <h3>{{ money_format("%.2n", App\Organization::totalPaid('stripe')) }}</h3>
                </div>
                <div class="card-footer text-muted">Stripe</div>
            </div>
            <div class="card text-center">
                <div class="card-block">
                    <h3>{{ money_format("%.2n", App\Organization::totalPaid('other')) }}</h3>
                </div>
                <div class="card-footer text-muted">Check / Other</div>
            </div>
        </div>

        <hr>

        <header class="d-flex justify-content-between">
            <h1>Churches</h1>
            <p>
                <a class="btn btn-secondary" href="{{ action('OrganizationController@create') }}">Add Church</a>
            </p>
        </header>


        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Church</th>
                    <th>Contact</th>
                    <th>Balance</th>
                    <th class="text-center">Tickets</th>
                    <th class="text-center">Registered</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($organizations as $organization)
                    <tr>
                        <td>
                                <a href="{{ action('OrganizationController@show', $organization) }}">{{ $organization->church->name }}</a>
                                <div>
                                    {{ $organization->church->location }}
                                    <small style="display:block;font-weight: normal;color:#aaa">{{ $organization->created_at->format('M j, Y g:i A') }}</small>
                                </div>
                        </td>
                        <td>
                            @if ($organization->contact)
                                {{ $organization->contact->name }} <br> <small>{{ $organization->contact->email }}</small>
                            @endif
                        </td>
                        <td>{{ money_format('%.2n', $organization->balance) }}</td>
                        <td class="text-center">{{ $organization->num_tickets }}</td>
                        <td class="text-center" class="{{ $organization->attendees->active()->count() == $organization->num_tickets ? 'positive' : '' }} {{ $organization->attendees->active()->count() > $organization->num_tickets ? 'negative' : '' }}">{{ $organization->attendees->active()->count() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $organizations->links() }}
    </div>
@stop
