<div class="form-group">
    {{ Form::label('agegroup', 'Type', ['class' => 'control-label']) }}
    {{ Form::select('agegroup', ['student' => 'Student', 'leader' => 'Leader'], null, ['id' => 'agegroup', 'class' => 'form-control']) }}
</div>
<div class="form-group">
    {{ Form::label('first_name', 'First Name', ['class' => 'control-label']) }}
    {{ Form::text('first_name', null, ['id' => 'first_name', 'class' => 'form-control']) }}
</div>
<div class="form-group">
    {{ Form::label('last_name', 'Last Name', ['class' => 'control-label']) }}
    {{ Form::text('last_name', null, ['id' => 'last_name', 'class' => 'form-control']) }}
</div>
<fieldset>
    <legend>Gender</legend>
    {{ Form::radio('gender', 'M', null, ['id' => 'gender--M']) }} <label for="gender--M">Male</label>
    {{ Form::radio('gender', 'F', null, ['id' => 'gender--F']) }} <label for="gender--F">Female</label>
</fieldset>
<div class="form-group">
    {{ Form::label('grade', 'Grade', ['class' => 'control-label']) }}
    {{ Form::select('grade', $gradeOptions, null, ['id' => 'grade', 'class' => 'form-control']) }}
</div>
<div class="form-group">
    {{ Form::label('allergies', 'Special Considerations (medical/food allergies, physical or vision/hearing impairment, etc.)', ['class' => 'control-label']) }}
    {{ Form::text('allergies', null, ['id' => 'allergies', 'class' => 'form-control']) }}
</div>
@if ($order->organization->slug == 'pcc')
    <div class="form-group">
        {{ Form::label('birthdate', 'Birthdate', ['class' => 'control-label']) }}
        {{ Form::text('birthdate', null, ['id' => 'birthdate', 'class' => 'form-control js-form-input-date']) }}
    </div>
@endif
@can ('record-transactions', $order->organization)
    <div class="form-group">
        {{ Form::label('price', 'Price', ['class' => 'control-label']) }}
        {{ Form::text('price', $ticket_price ?? 0, ['id' => 'price', 'class' => 'form-control']) }}
    </div>
@endif

<footer style="display:flex;justify-content:space-between;">
    <div class="form-group form-actions">
        <button class="btn btn-primary">{{ $submitButtonText }}</button>
        <a href="{{ route('order.show', $order) }}" style="margin-left:1rem">Go Back</a>
    </div>
</footer>
