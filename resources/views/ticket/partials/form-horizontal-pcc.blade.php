<div class="form-group row">
    <label for="email" class="col-md-3 col-form-label text-md-right">Email</label>
    <div class="col-md-6">
        <input type="email" name="ticket[email]" id="email" value="{{ old('ticket.email', $ticket->email) }}" class="form-control">
    </div>
</div>

<div class="form-group row">
    <label for="phone" class="col-md-3 col-form-label text-md-right">Phone</label>
    <div class="col-md-6">
        <input type="text" name="ticket[phone]" id="phone" value="{{ old('ticket.phone', $ticket->phone) }}" class="form-control">
    </div>
</div>

<div class="form-group row">
    <label for="birthdate" class="col-md-3 col-form-label text-md-right">Birthdate</label>
    <div class="col-md-6">
        <input type="text" name="ticket[birthdate]" id="birthdate" value="{{ old('ticket.birthdate', $ticket->birthdate ? $ticket->birthdate->format('m/d/Y') : null) }}" class="form-control">
    </div>
</div>

<div class="form-group row">
    <label for="shirtsize" class="col-md-3 col-form-label text-md-right">Shirt Size</label>
    <div class="col-md-6">
        <input type="text" name="ticket_data[shirtsize]" id="shirtsize" value="{{ old('ticket_data.shirtsize', $ticket->shirtsize) }}" class="form-control">
    </div>
</div>

<div class="form-group row">
    <label for="school" class="col-md-3 col-form-label text-md-right">School</label>
    <div class="col-md-6">
        <input type="text" name="ticket_data[school]" id="school" value="{{ old('ticket_data.school', $ticket->school) }}" class="form-control">
    </div>
</div>

<div class="form-group row">
    <label for="roommate_requested" class="col-md-3 col-form-label text-md-right">Roommate Requested</label>
    <div class="col-md-6">
        <input type="text" name="ticket_data[roommate_requested]" id="roommate_requested" value="{{ old('ticket_data.roommate_requested', $ticket->roommate_requested) }}" class="form-control">
    </div>
</div>

<div class="form-group row">
    <label for="squad" class="col-md-3 col-form-label text-md-right">Squad</label>
    <div class="col-md-6">
        <input type="text" name="ticket[squad]" id="squad" value="{{ old('ticket.squad', $ticket->squad) }}" class="form-control">
    </div>
</div>

<div class="form-group row">
    <label for="leader" class="col-md-3 col-form-label text-md-right">Leader</label>
    <div class="col-md-6">
        <input type="text" name="ticket_data[leader]" id="leader" value="{{ old('ticket_data.leader', $ticket->leader) }}" class="form-control">
    </div>
</div>

<div class="form-group row">
    <label for="bus" class="col-md-3 col-form-label text-md-right">Bus</label>
    <div class="col-md-6">
        <input type="text" name="ticket_data[bus]" id="bus" value="{{ old('ticket_data.bus', $ticket->bus) }}" class="form-control">
    </div>
</div>

<div class="form-group row">
    <label for="travel_plans" class="col-md-3 col-form-label text-md-right">Travel Plans</label>
    <div class="col-md-6">
        <input type="text" name="ticket_data[travel_plans]" id="travel_plans" value="{{ old('ticket_data.travel_plans', $ticket->travel_plans) }}" class="form-control">
    </div>
</div>

<div class="form-group row">
    <label for="price" class="col-md-3 col-form-label text-md-right">Price</label>
    <div class="col-md-6">
        <input type="text" name="ticket[price]" id="price" value="{{ old('ticket.price', $ticket->price / 100) }}" class="form-control">
    </div>
</div>

<div class="form-group row">
    <div class="col-md-6 offset-md-3">
        <div class="form-check">
            <input type="hidden" name="ticket_data[pcc_waiver]" value="0" style="display:none">
            <label class="form-check-label">
                <input type="checkbox" name="ticket_data[pcc_waiver]" id="pcc_waiver" value="1" class="form-check-input" @if (old('ticket_data.pcc_waiver', $ticket->pcc_waiver)) checked @endif>
                PCC Waiver?
            </label>
        </div>
    </div>
</div>
