@extends('layouts.semantic')

@section('content')

    <div class="ui container Dashboard">
        <div class="Dashboard__header">
            <h1>{{ $organization->church->name }}</h1>
        </div>
        <div class="ui info message" style="margin-bottom:2rem">
            <h5>THE PASSION CAMP PORTAL IS HERE!</h5>
            <p>We want to make all things Passion Camp as EASY as possible for you so this is going to be a valuable resource as you prepare for camp! The portal is the place for you to track your payments, provide us with your student and leader information, send waivers, and create your rooming list.</p>
            <a data-open="portalIntroVideo" href="#">Check out the full portal tutorial!</a>
        </div>
        <div class="ui two column stackable grid">
            <div class="column">
                <div class="Statistics_container">
                    <h3>Tickets</h3>
                    <div class="ui statistics">
                        <div class="statistic">
                            <div class="value">{{ $organization->tickets_remaining_count }}</div>
                            <div class="label">Remaining</div>
                        </div>
                        <div class="ui small horizontal statistics">
                            <div class="statistic" style="margin-top: 0;">
                                <div class="value">{{ $organization->orders->ticket_count }}</div>
                                <div class="label">Registered</div>
                            </div>
                            <div class="statistic" style="margin-top: 0;">
                                <div class="value">{{ $organization->ticket_count }}</div>
                                <div class="label">Purchased</div>
                            </div>                    
                        </div>
                    </div>                
                </div>
            </div>
            <div class="column">
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
            </div>
            <div class="column">
                @can ('record-transactions', $organization)
                    @include('organization.partials.registration_summary')
                @endcan
            </div>
        </div>
    </div>
@stop
