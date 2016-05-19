<div class="field">
    {{ Form::label('first_name', 'First Name') }}
    {{ Form::text('first_name', null, ['id' => 'first_name']) }}
</div>
<div class="field">
    {{ Form::label('last_name', 'Last Name') }}
    {{ Form::text('last_name', null, ['id' => 'last_name']) }}
</div>
<div class="field">
    {{ Form::label('email', 'Email Address') }}
    {{ Form::email('email', null, ['id' => 'email']) }}
</div>
<div class="field">
    {{ Form::label('phone', 'Phone Number') }}
    {{ Form::text('phone', null, ['id' => 'phone']) }}
</div>