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
            <h1>Update Attendee</h1>
            @if ($ticket->order->organization->slug == 'pcc')
                <a href="{{ action('OrderController@show', $ticket->order) }}">Order #{{ $ticket->order->id }}</a>
            @endif
        </header>

        @include('errors.validation')

        <form action="{{ action('TicketController@update', $ticket) }}" method="POST">
            {{ method_field('PATCH') }}
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

                @else

                    <h4 class="card-header"><template v-if="agegroup == 'student'">Parent/Guardian</template> Contact Information</h4>
                    <div class="card-block">
                        <div class="form-group row">
                            <label for="name" class="col-md-3 col-form-label text-md-right">Name</label>
                            <div class="col-md-6">
                                <input type="text" name="contact[name]" id="name" class="form-control" value="{{ old('contact.name', $ticket->order->user->person->name) }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-3 col-form-label text-md-right">Email</label>
                            <div class="col-md-6">
                                <input type="email" name="contact[email]" id="email" class="form-control" value="{{ old('contact.email', $ticket->order->user->person->email) }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone" class="col-md-3 col-form-label text-md-right">Phone</label>
                            <div class="col-md-6">
                                <input type="tel" name="contact[phone]" id="phone" class="form-control" value="{{ old('contact.phone', $ticket->order->user->person->phone) }}">
                            </div>
                        </div>
                    </div>

                @endif
                <div class="card-block">
                    <div class="row">
                        <div class="col offset-md-3">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        @if (auth()->user()->isAdmin())
            <footer class="text-right">
                @can('cancel', $ticket)
                    <a href="{{ action('TicketController@delete', $ticket) }}" class="btn btn-outline-warning btn-sm" onclick="event.preventDefault(); document.getElementById('cancel-form').submit();">Cancel</a>
                @endcan
                <a href="{{ action('TicketController@delete', $ticket) }}" class="btn btn-outline-danger btn-sm" onclick="event.preventDefault(); document.getElementById('delete-form').submit();">Delete</a>
            </footer>

            <form action="{{ action('TicketController@cancel', $ticket) }}" method="POST" id="cancel-form">
                {{ method_field('PATCH') }}
                {{ csrf_field() }}
            </form>
            <form action="{{ action('TicketController@delete', $ticket) }}" method="POST" id="delete-form">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
            </form>
        @endif
    </div>
@stop
