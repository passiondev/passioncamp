@extends('layouts.bootstrap4')

@section('content')
    <div class="container-fluid">
        <header class="d-lg-flex justify-content-between align-items-center mb-lg-2">
            <h1 class="mb-2 mb-lg-0">Waivers</h1>
        </header>

        @if (config('passioncamp.waiver_test_mode'))
            <div class="alert alert-warning text-center">
                Test mode enabled. Waivers will be sent to <strong>{{ auth()->user()->email }}</strong> and won't count against quota.
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @livewire('waivers-table')
    </div>
@stop
