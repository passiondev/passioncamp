@extends('layouts.bootstrap4')


@section('content')
<div class="container">
    <h1>Passion Camp Group Forms</h1>

    <div class="list-group mt-4" style="max-width: 30rem">
        <a
            class="list-group-item justify-content-between"
            href="https://passion-forms.formstack.com/forms/pc2020registrationrequests"
            target="_blank"
        >
            Registration Add/Drop Request Form
        </a>
        <a
            class="list-group-item justify-content-between"
            href="https://passion-forms.formstack.com/forms/pc2020hotelrequests"
            target="_blank"
        >
            Hotel Request Form
        </a>
        <div class="list-group-item">
            <div class="d-flex justify-content-between align-items-center w-100">
                Group Leader Agreement
                @if (auth()->user()->organization->setting('group_leader_agreement_signed'))
                    <span class="badge badge-success badge-pill">COMPLETE</span>
                @else
                    <strong style="font-size:80%;">Pending</strong>
                @endif
            </div>
            <p style="font-size:85%" class="text-muted mt-2 mb-0">You will receive your group leader agreement via email from HelloSign soon.</p>
        </div>
        <div class="list-group-item justify-content-between text-muted">
            <span style="color:#cad2e2">Hotel Authorization Form</span>
            <strong style="font-size:80%;">Coming Soon</strong>
        </div>
        <div class="list-group-item justify-content-between text-muted">
            <span style="color:#cad2e2">Student and Leader Waivers</span>
            <strong style="font-size:80%;">Coming Soon</strong>
        </div>
        <div class="list-group-item justify-content-between text-muted">
            <span style="color:#cad2e2">Parking Pass Request Form</span>
            <strong style="font-size:80%;">Coming Soon</strong>
        </div>
    </div>
</div>







@endsection
