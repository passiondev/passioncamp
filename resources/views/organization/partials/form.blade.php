<h3>Church</h3>
<div class="form-group">
    <label for="church__name">Name</label>
    <input type="text" name="church[name]" id="church__name" value="{{ old('church[name]', $organization->church->name) }}" class="form-control">
</div>
<div class="form-group">
    <label for="church__street">Street</label>
    <input type="text" name="church[street]" id="church__street" value="{{ old('church[street]', $organization->church->street) }}" class="form-control">
</div>
<div class="form-group">
    <label for="church__city">City</label>
    <input type="text" name="church[city]" id="church__city" value="{{ old('church[city]', $organization->church->city) }}" class="form-control">
</div>
<div class="form-group">
    <label for="church__state">State</label>
    <input type="text" name="church[state]" id="church__state" value="{{ old('church[state]', $organization->church->state) }}" class="form-control">
</div>
<div class="form-group">
    <label for="church__zip">Zip Code</label>
    <input type="text" name="church[zip]" id="church__zip" value="{{ old('church[zip]', $organization->church->zip) }}" class="form-control">
</div>
<div class="form-group">
    <label for="church__website">Website</label>
    <input type="text" name="church[website]" id="church__website" value="{{ old('church[website]', $organization->church->website) }}" class="form-control">
</div>
<div class="form-group">
    <label for="church__pastor_name">Pastor Name</label>
    <input type="text" name="church[pastor_name]" id="church__pastor_name" value="{{ old('church[pastor_name]', $organization->church->pastor_name) }}" class="form-control">
</div>

<h3>Student Pastor</h3>
<div class="form-group">
    <label for="student_pastor__first_name">First Name</label>
    <input type="text" name="student_pastor[first_name]" id="student_pastor__first_name" value="{{ old('student_pastor[first_name]', $organization->studentPastor->first_name) }}" class="form-control">
</div>
<div class="form-group">
    <label for="student_pastor__last_name">Last Name</label>
    <input type="text" name="student_pastor[last_name]" id="student_pastor__last_name" value="{{ old('student_pastor[last_name]', $organization->studentPastor->last_name) }}" class="form-control">
</div>
<div class="form-group">
    <label for="student_pastor__email">Email Address</label>
    <input type="email" name="student_pastor[email]" id="student_pastor__email" value="{{ old('student_pastor[email]', $organization->studentPastor->email) }}" class="form-control">
</div>
<div class="form-group">
    <label for="student_pastor__phone">Phone Number</label>
    <input type="text" name="student_pastor[phone]" id="student_pastor__phone" value="{{ old('student_pastor[phone]', $organization->studentPastor->phone) }}" class="form-control">
</div>

<h3>Contact</h3>
<div class="form-group">
    <label for="contact__first_name">First Name</label>
    <input type="text" name="contact[first_name]" id="contact__first_name" value="{{ old('contact[first_name]', $organization->contact->first_name) }}" class="form-control">
</div>
<div class="form-group">
    <label for="contact__last_name">Last Name</label>
    <input type="text" name="contact[last_name]" id="contact__last_name" value="{{ old('contact[last_name]', $organization->contact->last_name) }}" class="form-control">
</div>
<div class="form-group">
    <label for="contact__email">Email Address</label>
    <input type="email" name="contact[email]" id="contact__email" value="{{ old('contact[email]', $organization->contact->email) }}" class="form-control">
</div>
<div class="form-group">
    <label for="contact__phone">Phone Number</label>
    <input type="text" name="contact[phone]" id="contact__phone" value="{{ old('contact[phone]', $organization->contact->phone) }}" class="form-control">
</div>

{{--
<fieldset>
    <legend>Flags</legend>
    <div class="form-group">
        <div class="form-check">
            <label class="form-check-label">
                <input type="checkbox" name="flags[]" value="roominglist" class="form-check-input" @if (in_array('roominglist', old('flags', $organization->flags->toArray()))) checked @endif>
                Rooming List
            </label>
        </div>
    </div>
</fieldset>
--}}
