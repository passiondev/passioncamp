@extends('layouts.app')

@section('content')
    <div class="container Dashboard">
        <div class="Dashboard__header">
            <h1>{{ $organization->church->name }}</h1>
        </div>
        <div class="row">
            <div class="small-6 columns">
                <div class="Statistics_container">
                    <h4>Tickets</h4>
                    <div class="Statistics">
                        <div class="Statistic --huge">
                            <div class="Statistic__value">{{ $organization->tickets_remaining_count }}</div>
                                <div class="Statistic__label">Remaining</div>
                        </div>
                        <div class="Statistics --vertical">
                            <div class="Statistic --small">
                                <div class="Statistic__value">{{ $organization->orders->ticket_count }}</div>
                                <div class="Statistic__label">Registered</div>
                            </div>
                            <div class="Statistic --small">
                                <div class="Statistic__value">{{ $organization->ticket_count }}</div>
                                <div class="Statistic__label">Purchased</div>
                            </div>                    
                        </div>
                    </div>                
                </div>
            </div>
            <div class="small-6 columns">
                <div class="Statistics_container">
                    <h4>Attendees</h4>
                    <div class="Statistics">
                        <div class="Statistic --huge">
                            <div class="Statistic__value">{{ $organization->orders->ticket_count }}</div>
                                <div class="Statistic__label">Registered</div>
                        </div>
                        <div class="Statistics --vertical">
                            <div class="Statistic --small">
                                <div class="Statistic__value">{{ $organization->orders->student_count }}</div>
                                <div class="Statistic__label">Students</div>
                            </div>
                            <div class="Statistic --small">
                                <div class="Statistic__value">{{ $organization->orders->leader_count }}</div>
                                <div class="Statistic__label">Leaders</div>
                            </div>                    
                        </div>
                    </div>                
                </div>                
            </div>
        </div>
        <div class="row">
            <div class="medium-6 columns">
                @include('organization.partials.billing_summary')
            </div>
            <div class="medium-6 columns">
                @can ('record-transactions', $organization)
                    @include('organization.partials.registration_summary')
                @endcan
            </div>
        </div>
    </div>
@stop
