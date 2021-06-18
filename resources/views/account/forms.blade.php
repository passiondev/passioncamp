@extends('layouts.bootstrap4')


@section('content')
<div class="container">
    <h1>Passion Camp Group Forms</h1>

    <div class="list-group mt-4" style="max-width: 30rem">
        <a
            class="list-group-item justify-content-between"
            href="https://passion-forms.formstack.com/forms/pc2021registrationrequests"
            target="_blank"
        >
            Registration Add/Drop Request Form
        </a>
        <a
            class="list-group-item justify-content-between"
            href="https://passion-forms.formstack.com/forms/pc2021hotelrequests"
            target="_blank"
        >
            Hotel Request Form
        </a>
        <a
            class="list-group-item justify-content-between"
            href="https://app.hellosign.com/s/DSy0MS5H"
            target="_blank"
        >
            Group Leader Agreement
        </a>
        <a
            class="list-group-item justify-content-between"
            href="https://app.hellosign.com/s/51SW6SBn"
            target="_blank"
        >
            Hotel Policy Agreement
        </a>
        <div class="list-group-item justify-content-between text-muted">
            <span style="color:#cad2e2">Parking Pass Request Form</span>
            <strong style="font-size:80%;">Coming Soon</strong>
        </div>
    </div>
</div>







@endsection
