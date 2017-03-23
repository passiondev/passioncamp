@extends('layouts.bootstrap4')

@section('head')
<script>
    window.store = {!! json_encode([
        'agegroup' => old('ticket.agegroup', $ticket->agegroup)
    ]) !!};
</script>
@endsection

@section('content')
    <div class="container">
        <header>
            <h1>Add Attendee</h1>
        </header>

        @include('errors.validation')

        <form action="{{ action('OrderTicketController@store', $ticket->order) }}" method="POST">
            {{ csrf_field() }}

            <div class="card mb-3">
                <h4 class="card-header">Attendee</h4>
                <div class="card-block">
                    @include('ticket.partials.form-horizontal')
                </div>

                @if ($ticket->order->organization->slug == 'pcc')
                    <h4 class="card-header">PCC Info</h4>
                    <div class="card-block">
                        @include('ticket.partials.form-horizontal-pcc')
                    </div>
                @endif

                <div class="card-block">
                    <div class="row">
                        <div class="col offset-md-3">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop
