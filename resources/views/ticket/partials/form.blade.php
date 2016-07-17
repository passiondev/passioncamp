<ticket-form inline-template>
    <div class="field">
        {{ Form::label('agegroup', 'Type') }}
        {{ Form::select('agegroup', ['student' => 'Student', 'leader' => 'Leader'], null, ['id' => 'agegroup', 'class' => 'ui dropdown', 'v-model' => 'agegroup']) }}
    </div>
    <div class="field">
        {{ Form::label('first_name', 'First Name') }}
        {{ Form::text('first_name', null, ['id' => 'first_name']) }}
    </div>
    <div class="field">
        {{ Form::label('last_name', 'Last Name') }}
        {{ Form::text('last_name', null, ['id' => 'last_name']) }}
    </div>
    <div class="grouped fields">
        <label>Gender</label>
        <div class="field">
            <div class="ui radio checkbox">
                {{ Form::radio('gender', 'M', null, ['id' => 'gender--M']) }}
                <label for="gender--M">Male</label>
            </div>
        </div>
        <div class="field">
            <div class="ui radio checkbox">
                {{ Form::radio('gender', 'F', null, ['id' => 'gender--F']) }}
                <label for="gender--F">Female</label>
            </div>
        </div>
    </div>
    <div class="field">
        {{ Form::label('grade', 'Grade') }}
        {{ Form::select('grade', (['' => ''] + $gradeOptions), null, ['id' => 'grade', 'class' => 'ui dropdown']) }}
    </div>
    <div class="field">
        {{ Form::label('allergies', 'Special Considerations (medical/food allergies, physical or vision/hearing impairment, etc.)') }}
        {{ Form::text('allergies', null, ['id' => 'allergies']) }}
    </div>
    @if ($order->organization->slug == 'pcc')
        <div class="field">
            {{ Form::label('email', 'Email') }}
            {{ Form::email('email', null, ['id' => 'email']) }}
        </div>
        <div class="field">
            {{ Form::label('phone', 'Phone') }}
            {{ Form::text('phone', null, ['id' => 'phone']) }}
        </div>
        <div class="field">
            {{ Form::label('birthdate', 'Birthdate') }}
            {{ Form::text('birthdate', null, ['id' => 'birthdate', 'class' => 'form-control js-form-input-date']) }}
        </div>
        <div class="field">
            {{ Form::label('shirtsize', 'Shirt Size') }}
            {{ Form::text('shirtsize', null, ['id' => 'shirtsize']) }}
        </div>
        <div class="field">
            {{ Form::label('school', 'School') }}
            {{ Form::text('school', null, ['id' => 'school']) }}
        </div>
        <div class="field">
            {{ Form::label('roommate_requested', 'Roommate Requested') }}
            {{ Form::text('roommate_requested', null, ['id' => 'roommate_requested']) }}
        </div>
        <div class="field">
            {{ Form::label('squad', 'Squad') }}
            {{ Form::text('squad', null, ['id' => 'squad']) }}
        </div>
        <div class="field">
            {{ Form::label('leader', 'Leader') }}
            {{ Form::text('leader', null, ['id' => 'leader']) }}
        </div>
        <div class="field">
            {{ Form::label('bus', 'Bus') }}
            {{ Form::text('bus', null, ['id' => 'bus']) }}
        </div>
    @endif
    @can ('record-transactions', $order->organization)
        <div class="field">
            {{ Form::label('price', 'Price') }}
            {{ Form::text('price', $ticket_price ?? 0, ['id' => 'price']) }}
        </div>
    @endif
    @if ($order->organization->slug == 'pcc')
        <div class="inline field">
            {{ Form::hidden('is_checked_in', 0) }}
            <div class="ui toggle checkbox">
                {{ Form::checkbox('is_checked_in', 1, $ticket->is_checked_in, ['id' => 'is_checked_in']) }}
                <label for="is_checked_in">Checked In?</label>
            </div>
        </div>
    @endif


    <button class="ui primary button">{{ $submitButtonText }}</button>
    
    @unless (app('url')->previous() == app('url')->full())
        <a href="{{ app('url')->previous() }}" style="margin-left:1rem">Go Back</a>
    @endunless
</ticket-form>

