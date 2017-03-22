@extends('layouts.bootstrap4')

@section('content')

    <div class="ui container Dashboard">
        <div class="row mb-5">
            <div class="col">
                <div class="Statistics_container">
                    <h3>Registrations</h3>
                    <div class="statistics">
                        <div class="statistic statistic-xl">
                            <div class="value">{{ $organization->tickets_remaining_count }}</div>
                            <div class="label">Remaining</div>
                        </div>
                        <div class="horizontal statistics">
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
                    <div class="statistics">
                        <div class="statistic statistic-xl">
                            <div class="value">{{ $organization->orders->ticket_count }}</div>
                            <div class="label">Registered</div>
                        </div>
                        <div class="horizontal statistics">
                            <div class="purple statistic">
                                <div class="value" style="color:purple">{{ $organization->orders->student_count }}</div>
                                <div class="label">Students</div>
                            </div>
                            <div class="teal statistic">
                                <div class="value" style="color:teal">{{ $organization->orders->leader_count }}</div>
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
