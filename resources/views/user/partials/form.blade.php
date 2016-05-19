@include ('errors.validation')

@if (auth()->user()->is_super_admin)
    <div class="field">
        {{ Form::label('organization', 'Organization') }}
        {{ Form::select('organization', ['ADMIN' => 'PASSION CAMP ADMIN'] + $organizationOptions, null, ['id' => 'organization', 'class' => 'ui dropdown']) }}
    </div>
@endif

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

<button class="ui primary button" type="submit">{{ $action_text or 'Create User' }}</button>
