@extends('layouts.bootstrap4')

@section('content')

    <div class="ui container Dashboard">
        <div class="row mb-5">
            <div class="col">
                <div class="Statistics_container">
                    <h3>Registrations</h3>
                    <div class="ui statistics">
                        <div class="statistic">
                            <div class="value">{{ $organization->tickets_remaining_count }}</div>
                            <div class="label">Remaining</div>
                        </div>
                        <div class="ui small horizontal statistics">
                            <div class="statistic">
                                <div class="value">{{ $organization->ticket_count }}</div>
                                <div class="label">Purchased</div>
                            </div>
                            <div class="statistic">
                                <div class="value">{{ $organization->orders->ticket_count }}</div>
                                <div class="label">Registered</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="Statistics_container">
                    <h3>Attendees</h3>
                    <div class="ui statistics">
                        <div class="statistic">
                            <div class="value">{{ $organization->orders->ticket_count }}</div>
                            <div class="label">Registered</div>
                        </div>
                        <div class="ui small horizontal statistics">
                            <div class="purple statistic" style="margin-top: 0;">
                                <div class="value">{{ $organization->orders->student_count }}</div>
                                <div class="label">Students</div>
                            </div>
                            <div class="teal statistic" style="margin-top: 0;">
                                <div class="value">{{ $organization->orders->leader_count }}</div>
                                <div class="label">Leaders</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ui two column stackable grid">
            <div class="column">
                @include('organization.partials.billing_summary')
                <a href="{{ action('Account\PaymentController@index') }}" class="btn btn-primary">Make Payment</a>
            </div>
        </div>
    </div>
@stop
