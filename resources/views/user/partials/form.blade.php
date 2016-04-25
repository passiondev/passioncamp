@include ('errors.validation')

@if (auth()->user()->is_super_admin)
    <div class="form-group">
        {{ Form::label('organization', 'Organization', ['class' => 'control-label']) }}
        {{ Form::select('organization', ['ADMIN' => 'PASSION CAMP ADMIN'] + $organizationOptions, null, ['id' => 'organization', 'class' => 'form-control']) }}
    </div>
@endif

<div class="form-group">
    {{ Form::label('first_name', 'First Name', ['class' => 'control-label']) }}
    {{ Form::text('first_name', null, ['id' => 'first_name', 'class' => 'form-control']) }}
</div>

<div class="form-group">
    {{ Form::label('last_name', 'Last Name', ['class' => 'control-label']) }}
    {{ Form::text('last_name', null, ['id' => 'last_name', 'class' => 'form-control']) }}
</div>

<div class="form-group">
    {{ Form::label('email', 'Email Address', ['class' => 'control-label']) }}
    {{ Form::email('email', null, ['id' => 'email', 'class' => 'form-control']) }}
</div>

<div class="form-group form-actions">
    <button type="submit">{{ $action_text or 'Create User' }}</button>
</div>