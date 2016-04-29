@extends('layouts.app')

@section('content')
    <div class="container Dashboard">
        <div class="Dashboard__header">
            <h1>{{ $organization->church->name }}</h1>
        </div>
        <div class="callout primary" style="margin-bottom:2rem">
            <h5>THE PASSION CAMP PORTAL IS HERE!</h5>
            <p>We want to make all things Passion Camp as EASY as possible for you so this is going to be a valuable resource as you prepare for camp! The portal is the place for you to track your payments, provide us with your student and leader information, send waivers, and create your rooming list.</p>
            <a data-open="portalIntroVideo" href="#">Check out the full portal tutorial!</a>
        </div>
        <div class="row">
            <div class="small-6 columns">
                <div class="Statistics_container">
                    <h4>Tickets</h4>
                    <div class="Statistics">
                        <div class="Statistic Statistic--huge">
                            <div class="Statistic__value">{{ $organization->tickets_remaining_count }}</div>
                                <div class="Statistic__label">Remaining</div>
                        </div>
                        <div class="Statistics Statistics--vertical">
                            <div class="Statistic Statistic--small">
                                <div class="Statistic__value">{{ $organization->orders->ticket_count }}</div>
                                <div class="Statistic__label">Registered</div>
                            </div>
                            <div class="Statistic Statistic--small">
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
                        <div class="Statistic Statistic--huge">
                            <div class="Statistic__value">{{ $organization->orders->ticket_count }}</div>
                                <div class="Statistic__label">Registered</div>
                        </div>
                        <div class="Statistics Statistics--vertical">
                            <div class="Statistic Statistic--small">
                                <div class="Statistic__value">{{ $organization->orders->student_count }}</div>
                                <div class="Statistic__label">Students</div>
                            </div>
                            <div class="Statistic Statistic--small">
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

@section('foot')
<script>
$(function(){
    new Foundation.Reveal($('#portalIntroVideo'))
})
</script>
<div class="reveal" id="portalIntroVideo" data-reveal data-reset-on-close="true">
  <div class="flex-video widescreen">
    <iframe src="https://player.vimeo.com/video/164488615" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
  </div>
  <button class="close-button" data-close aria-label="Close modal" type="button">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endsection