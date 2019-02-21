@extends('layouts.pccstudents')

@section('content')
    <div class="container mb-5">
            <p class="lead">Registration Is Now Closed</p>

            @markdown($occurrence->closed_message)
    </div>
@stop
