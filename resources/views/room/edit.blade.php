@extends('layouts.bootstrap4')

@section('content')
    <div class="container">
        <h1>Edit Room</h1>
        <div class="card">
            <h4 class="card-header">{{ $room->name }}</h4>
            <div class="card-block">
                <form action="{{ action('RoomController@update', $room) }}" method="POST">
                    {{ method_field('PATCH') }}
                    {{ csrf_field() }}

                    <div class="form-group row">
                        <label for="capacity" class="col-md-3 col-form-label text-md-right">Capacity</label>
                        <div class="col-md-6">
                            <select name="capacity" id="capacity" class="form-control">
                                @foreach (range(4,5) as $capacity)
                                    <option value="{{ $capacity }}" @if (old('capacity', $room->capacity) == $capacity) selected @endif>{{ $capacity }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="description" class="col-md-3 col-form-label text-md-right">Room Type</label>
                        <div class="col-md-6">
                            <select name="description" id="description" class="form-control">
                                <option></option>
                                <option value="Leader Only" @if(old('description') == 'Leader Only') selected @endif>Leader Only</option>
                                <option value="Girls Room" @if(old('description') == 'Girls Room') selected @endif>Girls Room</option>
                                <option value="Guys Room" @if(old('description') == 'Guys Room') selected @endif>Guys Room</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="notes" class="col-md-3 col-form-label text-md-right">Notes</label>
                        <div class="col-md-6">
                            <input type="text" name="notes" id="notes" class="form-control" placeholder="ie, King Bed OK" value="{{ old('notes', $room->notes) }}">
                        </div>
                    </div>

                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" name="adp_required" id="adp_required" class="form-check-input" value="1" @if (old('adp_required', $room->adp_required)) checked @endif>
                            ADP Required
                        </label>
                    </div>

                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" name="king_preferred" id="king_preferred" class="form-check-input" value="1" @if (old('king_preferred', $room->king_preferred)) checked @endif>
                            King Bed Preferred
                        </label>
                    </div>

                    @if (Auth::user()->isSuperAdmin())
                        <div class="form-group row">
                            <label for="name" class="col-md-3 col-form-label text-md-right">Name</label>
                            <div class="col-md-6">
                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $room->name) }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="roomnumber" class="col-md-3 col-form-label text-md-right">Hotel</label>
                            <div class="col-md-6">
                                <p class="form-control-static"><strong>{{ $room->hotelName }}</strong></p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="roomnumber" class="col-md-3 col-form-label text-md-right">Room #</label>
                            <div class="col-md-6">
                                <input type="text" name="roomnumber" id="roomnumber" class="form-control" value="{{ old('roomnumber', $room->roomnumber) }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="confirmation_number" class="col-md-3 col-form-label text-md-right">Confirmation #</label>
                            <div class="col-md-6">
                                <input type="text" name="confirmation_number" id="confirmation_number" class="form-control" value="{{ old('confirmation_number', $room->confirmation_number) }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6 offset-md-3">
                                <input type="hidden" name="is_checked_in" value="0">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" name="is_checked_in" id="is_checked_in" class="form-check-input" value="1" @if (old('is_checked_in', $room->is_checked_in)) checked @endif>
                                        Checked In?
                                    </label>
                                </div>
                                <input type="hidden" name="is_key_received" value="0">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" name="is_key_received" id="is_key_received" class="form-check-input" value="1" @if (old('is_key_received', $room->is_key_received)) checked @endif>
                                        Key Received?
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col offset-md-3">
                            <button type="submit" class="btn btn-primary">Update Room</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


       {{--  @if (Auth::user()->isSuperAdmin())
        <br><br><br>
            <hr>
            <br><br><br>
            {{ Form::open(['route' => ['roominglist.destroy', $room], 'method' => 'DELETE', 'class' => 'ui form', 'onsubmit' => 'return confirm("Are you sure?")']) }}
                <button type="submit" class="ui tiny red button">Remove Room & Ticket Assignments</button>
            {{ Form::close() }}
        @endif --}}
    </div>
@stop
