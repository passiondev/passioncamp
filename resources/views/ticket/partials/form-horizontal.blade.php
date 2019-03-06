@if (auth()->user()->isAdmin())
    <div class="form-group row">
        <label for="agegroup" class="col-md-3 col-form-label text-md-right">Type</label>
        <div class="col-md-6">
            <select name="ticket[agegroup]" id="agegroup" class="form-control" v-model="agegroup">
                @foreach (['student' => 'Student', 'leader' => 'Leader'] as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
@endif

<div class="form-group row">
    <label for="first_name" class="col-md-3 col-form-label text-md-right">First Name</label>
    <div class="col-md-6">
        <input type="text" name="ticket[first_name]" id="first_name" value="{{ old('ticket.first_name', $ticket->first_name) }}" class="form-control">
    </div>
</div>

<div class="form-group row">
    <label for="last_name" class="col-md-3 col-form-label text-md-right">Last Name</label>
    <div class="col-md-6">
        <input type="text" name="ticket[last_name]" id="last_name" value="{{ old('ticket.last_name', $ticket->last_name) }}" class="form-control">
    </div>
</div>

<div class="form-group row">
    <label class="col-md-3 text-md-right col-form-label">Gender</label>
    <div class="col-md-6">
        <div class="form-check">
            <label class="form-check-label">
                <input type="radio" name="ticket[gender]" value="M" @if(old('ticket.gender', $ticket->gender) == 'M') checked @endif class="form-check-input">
                Male
            </label>
        </div>
        <div class="form-check">
            <label class="form-check-label">
                <input type="radio" name="ticket[gender]" value="F" @if(old('ticket.gender', $ticket->gender) == 'F') checked @endif class="form-check-input">
                Female
            </label>
        </div>
    </div>
</div>

<div class="form-group row">
    <label for="grade" class="col-md-3 col-form-label text-md-right">Grade</label>
    <div class="col-md-6">
        <select name="ticket[grade]" id="grade" class="form-control">
            <option value=""></option>
            @foreach ($gradeOptions as $key => $value)
                <option value="{{ $key }}" @if (old('ticket.grade', $ticket->grade) == $key) selected @endif>{{ $value }}</option>
            @endforeach
        </select>
        <p class="form-text text-muted mb-0">Completed as of June {{ config('passioncamp.year') }} </p>
    </div>
</div>

@if ($ticket->allergies)
    <div class="form-group row">
        <label for="allergies" class="col-md-3 col-form-label text-md-right">Special Considerations</label>
        <div class="col-md-6">
            <input type="text" name="ticket[allergies]" id="allergies" value="{{ old('ticket.allergies', $ticket->allergies) }}" class="form-control">
            <p class="form-text text-muted mb-0">(medical/food allergies, physical or vision/hearing impairment, etc)</p>
        </div>
    </div>
@endif

<div class="form-group row">
    <div class="col-md-6 offset-md-3">
        <ticket-considerations style="margin-bottom:0" :considerations="{{ json_encode(old('considerations', $ticket->considerations ?? [])) }}"></ticket-considerations>
    </div>
</div>
