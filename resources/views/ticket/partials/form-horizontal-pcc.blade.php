<div class="form-group row">
    <label for="email" class="col-md-3 col-form-label text-md-right">Email</label>
    <div class="col-md-6">
        <input type="email" name="email" id="email" value="{{ old('email', $ticket->email) }}" class="form-control">
    </div>
</div>

<div class="form-group row">
    <label for="phone" class="col-md-3 col-form-label text-md-right">Phone</label>
    <div class="col-md-6">
        <input type="text" name="phone" id="phone" value="{{ old('phone', $ticket->phone) }}" class="form-control">
    </div>
</div>

<div class="form-group row">
    <label for="birthdate" class="col-md-3 col-form-label text-md-right">Birthdate</label>
    <div class="col-md-6">
        <input type="text" name="birthdate" id="birthdate" value="{{ old('birthdate', $ticket->birthdate ? $ticket->birthdate->format('m/d/Y') : null) }}" class="form-control">
    </div>
</div>

<div class="form-group row">
    <label for="shirtsize" class="col-md-3 col-form-label text-md-right">Shirt Size</label>
    <div class="col-md-6">
        <input type="text" name="shirtsize" id="shirtsize" value="{{ old('shirtsize', $ticket->shirtsize) }}" class="form-control">
    </div>
</div>

<div class="form-group row">
    <label for="school" class="col-md-3 col-form-label text-md-right">School</label>
    <div class="col-md-6">
        <input type="text" name="school" id="school" value="{{ old('school', $ticket->school) }}" class="form-control">
    </div>
</div>

<div class="form-group row">
    <label for="roommate_requested" class="col-md-3 col-form-label text-md-right">Roommate Requested</label>
    <div class="col-md-6">
        <input type="text" name="roommate_requested" id="roommate_requested" value="{{ old('roommate_requested', $ticket->roommate_requested) }}" class="form-control">
    </div>
</div>

<div class="form-group row">
    <label for="squad" class="col-md-3 col-form-label text-md-right">Squad</label>
    <div class="col-md-6">
        <input type="text" name="squad" id="squad" value="{{ old('squad', $ticket->squad) }}" class="form-control">
    </div>
</div>

<div class="form-group row">
    <label for="leader" class="col-md-3 col-form-label text-md-right">Leader</label>
    <div class="col-md-6">
        <input type="text" name="leader" id="leader" value="{{ old('leader', $ticket->leader) }}" class="form-control">
    </div>
</div>

<div class="form-group row">
    <label for="bus" class="col-md-3 col-form-label text-md-right">Bus</label>
    <div class="col-md-6">
        <input type="text" name="bus" id="bus" value="{{ old('bus', $ticket->bus) }}" class="form-control">
    </div>
</div>

<div class="form-group row">
    <label for="travel_plans" class="col-md-3 col-form-label text-md-right">Travel Plans</label>
    <div class="col-md-6">
        <input type="text" name="travel_plans" id="travel_plans" value="{{ old('travel_plans', $ticket->travel_plans) }}" class="form-control">
    </div>
</div>

<div class="form-group row">
    <label for="price" class="col-md-3 col-form-label text-md-right">Price</label>
    <div class="col-md-6">
        <input type="text" name="price" id="price" value="{{ old('price', $ticket->price / 100) }}" class="form-control">
    </div>
</div>
