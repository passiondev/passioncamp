@extends('layouts.bootstrap4')


@section('content')
<div class="container">
    <h1>Passion Camp Group Forms</h1>

    <div class="list-group mt-4" style="max-width: 30rem">
        <a
            class="list-group-item justify-content-between"
            href="https://passionforms.formstack.com/forms/pc2020registrationrequests"
            target="_blank"
        >
            Registration Add/Drop Request Form
            <span class="badge badge-success badge-pill">NEW</span>
        </a>
        <a
            class="list-group-item justify-content-between"
            href="https://passion-forms.formstack.com/forms/pc2020hotelrequests"
            target="_blank"
        >
            Hotel Request Form
            <span class="badge badge-success badge-pill">NEW</span>
        </a>
        <span class="list-group-item justify-content-between text-muted">
            <span style="color:#cad2e2">Group Leader Agreement</span>
            <strong style="font-size:80%;">Coming Soon</strong>
        </span>
        <span class="list-group-item justify-content-between text-muted">
            <span style="color:#cad2e2">Hotel Authorization Form</span>
            <strong style="font-size:80%;">Coming Soon</strong>
        </span>
        <span class="list-group-item justify-content-between text-muted">
            <span style="color:#cad2e2">Student and Leader Waivers</span>
            <strong style="font-size:80%;">Coming Soon</strong>
        </span>
        <span class="list-group-item justify-content-between text-muted">
            <span style="color:#cad2e2">Parking Pass Request Form</span>
            <strong style="font-size:80%;">Coming Soon</strong>
        </span>
    </div>
</div>







@endsection
