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

        <form action="{{ action('Account\TicketController@store') }}" method="POST">
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

                <h4 class="card-header"><template v-if="agegroup == 'student'">Parent/Guardian</template> Contact Information</h4>
                <div class="card-block">
                    <div class="form-group row">
                        <label for="name" class="col-md-3 col-form-label text-md-right">Name</label>
                        <div class="col-md-6">
                            <input type="text" name="contact[name]" id="name" class="form-control" value="{{ old('contact.name') }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-md-3 col-form-label text-md-right">Email</label>
                        <div class="col-md-6">
                            <input type="email" name="contact[email]" id="email" class="form-control" value="{{ old('contact.email') }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="phone" class="col-md-3 col-form-label text-md-right">Phone</label>
                        <div class="col-md-6">
                            <input type="tel" name="contact[phone]" id="phone" class="form-control" value="{{ old('contact.phone') }}">
                        </div>
                    </div>

                </div>
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
