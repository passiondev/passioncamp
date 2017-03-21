<div class="form-group">
    <label for="agegroup">Type</label>
    <select name="agegroup" id="agegroup" class="form-control">
        @foreach (['student' => 'Student', 'leader' => 'Leader'] as $key => $value)
            <option value="{{ $key }}" @if (old('agegroup', $ticket->agegroup) == $key) selected @endif>{{ $value }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="first_name">First Name</label>
    <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $ticket->first_name) }}" class="form-control">
</div>

<div class="form-group">
    <label for="last_name">Last Name</label>
    <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $ticket->last_name) }}" class="form-control">
</div>

<fieldset class="form-group">
    <legend>Gender</legend>
    <div class="form-check form-check-inline">
        <label class="form-check-label">
            <input type="radio" name="gender" id="gender" value="M" @if(old('gender', $ticket->gender) == 'M') checked @endif class="form-check-input"> Male
        </label>
    </div>
    <div class="form-check form-check-inline">
        <label class="form-check-label">
            <input type="radio" name="gender" id="gender" value="F" @if(old('gender', $ticket->gender) == 'F') checked @endif class="form-check-input"> Female
        </label>
    </div>
</fieldset>

<div class="form-group">
    <label for="grade">Grade</label>
    <select name="grade" id="grade" class="form-control">
        <option></option>
        @foreach ($gradeOptions as $key => $value)
            <option value="{{ $key }}" @if (old('grade', $ticket->grade) == $key) selected @endif>{{ $value }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="allergies">Special Considerations (medical/food allergies, physical or vision/hearing impairment, etc</label>
    <input type="text" name="allergies" id="allergies" value="{{ old('allergies', $ticket->allergies) }}" class="form-control">
</div>

@if ($ticket->order->organization->slug == 'pcc')
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email', $ticket->email) }}" class="form-control">
    </div>
    <div class="form-group">
        <label for="phone">Phone</label>
        <input type="text" name="phone" id="phone" value="{{ old('phone', $ticket->phone) }}" class="form-control">
    </div>
    <div class="form-group">
        <label for="birthdate">Birthdate</label>
        <input type="text" name="birthdate" id="birthdate" value="{{ old('birthdate', $ticket->birthdate) }}" class="form-control">
    </div>
    <div class="form-group">
        <label for="shirtsize">Shirt Size</label>
        <input type="text" name="shirtsize" id="shirtsize" value="{{ old('shirtsize', $ticket->shirtsize) }}" class="form-control">
    </div>
    <div class="form-group">
        <label for="school">School</label>
        <input type="text" name="school" id="school" value="{{ old('school', $ticket->school) }}" class="form-control">
    </div>
    <div class="form-group">
        <label for="roommate_requested">Roommate Requested</label>
        <input type="text" name="roommate_requested" id="roommate_requested" value="{{ old('roommate_requested', $ticket->roommate_requested) }}" class="form-control">
    </div>
    <div class="form-group">
        <label for="squad">Squad</label>
        <input type="text" name="squad" id="squad" value="{{ old('squad', $ticket->squad) }}" class="form-control">
    </div>
    <div class="form-group">
        <label for="leader">Leader</label>
        <input type="text" name="leader" id="leader" value="{{ old('leader', $ticket->leader) }}" class="form-control">
    </div>
    <div class="form-group">
        <label for="bus">Bus</label>
        <input type="text" name="bus" id="bus" value="{{ old('bus', $ticket->bus) }}" class="form-control">
    </div>
    <div class="form-group">
        <label for="travel_plans">Travel Plans</label>
        <input type="text" name="travel_plans" id="travel_plans" value="{{ old('travel_plans', $ticket->travel_plans) }}" class="form-control">
    </div>

    <div class="form-group">
        <label for="price">Price</label>
        <input type="text" name="price" id="price" value="{{ old('price', $ticket->price) }}" class="form-control">
    </div>
@endif

@if ($ticket->order->organization->slug == 'pcc' && isset($ticket))
    <div class="inline field">
        {{ Form::hidden('is_checked_in', 0) }}
        <div class="ui toggle checkbox">
            {{ Form::checkbox('is_checked_in', 1, $ticket->is_checked_in, ['id' => 'is_checked_in']) }}
            <label for="is_checked_in">Checked In?</label>
        </div>
    </div>
@endif

@if ($ticket->order->organization->slug == 'pcc')
    <div class="inline field">
        {{ Form::hidden('pcc_waiver', 0) }}
        <div class="ui toggle checkbox">
            {{ Form::checkbox('pcc_waiver', 'X', null, ['id' => 'pcc_waiver']) }}
            <label for="pcc_waiver">PCC Waiver?</label>
        </div>
    </div>
@endif

<button class="btn btn-primary">{{ $submitButtonText }}</button>
