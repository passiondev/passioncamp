@extends('layouts.bootstrap4')

@section('content')

    <div class="ui container Dashboard">
        <div class="row mb-5">
            <div class="col">
                <div class="card text-center mb-3">
                    <h3 class="card-header">Registrations</h3>
                    <div class="card-block">
                        <div class="statistics justify-content-center">
                            <div class="statistic statistic-xl">
                                <div class="value">{{ $organization->tickets_remaining_count }}</div>
                                <div class="label">Remaining</div>
                            </div>
                            <div class="horizontal statistics">
                                <div class="statistic">
                                    <div class="value">{{ $organization->tickets_sum }}</div>
                                    <div class="label">Purchased</div>
                                </div>
                                <div class="statistic">
                                    <div class="value">{{ $organization->active_attendees_count }}</div>
                                    <div class="label">Registered</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center mb-3">
                    <h3 class="card-header">Attendees</h3>
                    <div class="card-block">
                        <div class="statistics justify-content-center">
                            <div class="statistic statistic-xl">
                                <div class="value">{{ $organization->active_attendees_count }}</div>
                                <div class="label">Registered</div>
                            </div>
                            <div class="horizontal statistics">
                                <div class="statistic color-student">
                                    <div class="value">{{ $organization->students_count }}</div>
                                    <div class="label">Students</div>
                                </div>
                                <div class="statistic color-leader">
                                    <div class="value">{{ $organization->leaders_count }}</div>
                                    <div class="label">Leaders</div>
                                </div>
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
